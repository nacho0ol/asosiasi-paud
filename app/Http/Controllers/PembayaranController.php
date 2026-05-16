<?php

namespace App\Http\Controllers;

use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Pembayaran;
use App\Models\Setting;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $q     = $request->q;
        $jenis = $request->jenis;
        $pembayarans = Pembayaran::with('memberDosen.dosen', 'memberProdi.prodi')
            ->when($q, fn($query) => $query
                ->where('no_kwitansi', 'like', "%$q%")
                ->orWhere('keterangan', 'like', "%$q%")
                ->orWhereHas('memberDosen.dosen', fn($d) => $d->where('nama', 'like', "%$q%"))
                ->orWhereHas('memberProdi.prodi', fn($p) => $p->where('nama_prodi', 'like', "%$q%"))
            )
            ->when($jenis, fn($query) => $query->where('jenis', $jenis))
            ->orderBy('created_at', 'desc')
            ->paginate(15)->withQueryString();
        return view('pembayaran.index', compact('pembayarans', 'q', 'jenis'));
    }

    public function create()
    {
        $memberDosens = MemberDosen::with('dosen')->where('status', 'aktif')->get();
        $memberProdis = MemberProdi::with('prodi')->where('status', 'aktif')->get();
        $setting = Setting::first();
        return view('pembayaran.create', compact('memberDosens', 'memberProdis', 'setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:dosen,prodi',
            'ref_id' => 'required|integer',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode' => 'required|in:tunai,transfer',
            'keterangan' => 'nullable|string',
        ]);

        $noKwitansi = 'KWT-' . date('Ymd') . '-' . str_pad(Pembayaran::count() + 1, 4, '0', STR_PAD_LEFT);

        $pembayaran = Pembayaran::create([
            'no_kwitansi' => $noKwitansi,
            'jenis' => $request->jenis,
            'ref_id' => $request->ref_id,
            'jumlah' => $request->jumlah,
            'tanggal_bayar' => $request->tanggal_bayar,
            'metode' => $request->metode,
            'keterangan' => $request->keterangan,
        ]);

        // Auto-lunas tagihan yang cocok
        $tagihan = Tagihan::where('jenis', $request->jenis)
            ->where('ref_id', $request->ref_id)
            ->where('status', 'belum_bayar')
            ->first();
        if ($tagihan) {
            $tagihan->update(['status' => 'lunas', 'pembayaran_id' => $pembayaran->id]);
        }

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Pembayaran $pembayaran)
    {
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        $memberDosens = MemberDosen::with('dosen')->get();
        $memberProdis = MemberProdi::with('prodi')->get();
        return view('pembayaran.edit', compact('pembayaran', 'memberDosens', 'memberProdis'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'metode' => 'required|in:tunai,transfer',
        ]);

        $pembayaran->update($request->only('jumlah', 'tanggal_bayar', 'metode', 'keterangan'));
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    // ==========================================
    // FITUR MIDTRANS MULAI DARI SINI
    // ==========================================

    public function bayar($id)
    {
        // Cari tagihan yang mau dibayar
        $tagihan = Tagihan::findOrFail($id);

        // Setup konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Bikin Order ID unik biar Midtrans nggak error kalau di-refresh
        $orderId = $tagihan->no_tagihan . '-' . time();

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => (int) $tagihan->jumlah,
            ),
            'customer_details' => array(
                'first_name' => 'Member',
                'last_name' => 'Asosiasi PAUD',
            ),
        );

        // Generate Token Snap
        $snapToken = Snap::getSnapToken($params);

        return view('pembayaran.bayar', compact('tagihan', 'snapToken'));
    }

    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {

                // Ekstrak no_tagihan asli (buang timestamp di belakangnya)
                $parts = explode('-', $request->order_id);
                array_pop($parts); 
                $noTagihan = implode('-', $parts);

                // Cari tagihan
                $tagihan = Tagihan::where('no_tagihan', $noTagihan)->first();

                if ($tagihan && $tagihan->status != 'lunas') {
                    
                    // 1. Catat Kuitansi Pembayaran Otomatis
                    $noKwitansi = 'KWT-' . date('Ymd') . '-' . str_pad(Pembayaran::count() + 1, 4, '0', STR_PAD_LEFT);
                    $pembayaran = Pembayaran::create([
                        'no_kwitansi' => $noKwitansi,
                        'jenis' => $tagihan->jenis,
                        'ref_id' => $tagihan->ref_id,
                        'jumlah' => $tagihan->jumlah,
                        'tanggal_bayar' => now(),
                        'metode' => 'midtrans',
                        'keterangan' => 'Auto-Payment via Midtrans (' . $request->payment_type . ')',
                    ]);

                    // 2. Ubah Status Tagihan Jadi Lunas
                    $tagihan->update([
                        'status' => 'lunas',
                        'pembayaran_id' => $pembayaran->id
                    ]);

                    // 3. AKTIFKAN MEMBER & PERPANJANG MASA BERLAKU
                    if ($tagihan->jenis == 'dosen') {
                        $member = MemberDosen::find($tagihan->ref_id);
                    } else {
                        $member = MemberProdi::find($tagihan->ref_id);
                    }

                    if ($member) {
                        $member->status = 'aktif';
                        $member->tanggal_mulai = now();
                        $member->tanggal_berakhir = now()->addYear();
                        $member->save();
                    }
                }
            }
        }
        return response()->json(['message' => 'Callback diterima mantap!']);
    }
}