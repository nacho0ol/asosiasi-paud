<?php

namespace App\Http\Controllers;

use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Pembayaran;
use App\Models\Setting;
use App\Models\Tagihan;
use Illuminate\Http\Request;

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
}
