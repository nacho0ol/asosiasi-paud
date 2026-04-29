<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Pembayaran;
use App\Models\Prodi;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        $tagihans = $query->orderBy('created_at', 'desc')->paginate(20);
   

        // Enrich dengan nama
        $tagihans->getCollection()->transform(function ($t) {
            if ($t->jenis === 'dosen') {
                $t->nama_ref = optional(Dosen::find($t->ref_id))->nama ?? '-';
            } else {
                $p = Prodi::find($t->ref_id);
                $t->nama_ref = $p ? $p->nama_prodi . ' - ' . $p->nama_universitas : '-';
            }
            return $t;
        });

        $totalBelumBayar = Tagihan::where('status', 'belum_bayar')->count();
        $totalLunas      = Tagihan::where('status', 'lunas')->count();

        return view('tagihan.index', compact('tagihans', 'totalBelumBayar', 'totalLunas'));
    }

    public function lunas(Tagihan $tagihan)
    {
        if ($tagihan->status === 'lunas') {
            return redirect()->back()->with('error', 'Tagihan sudah lunas.');
        }

        // Buat pembayaran otomatis
        $noKwitansi = 'KWT-' . date('Ymd') . '-' . str_pad(Pembayaran::count() + 1, 4, '0', STR_PAD_LEFT);
        $pembayaran = Pembayaran::create([
            'no_kwitansi'   => $noKwitansi,
            'jenis'         => $tagihan->jenis,
            'ref_id'        => $tagihan->ref_id,
            'jumlah'        => $tagihan->jumlah,
            'tanggal_bayar' => Carbon::today(),
            'metode'        => 'transfer',
            'keterangan'    => 'Pelunasan ' . $tagihan->no_tagihan,
        ]);

        $tagihan->update([
            'status'        => 'lunas',
            'pembayaran_id' => $pembayaran->id,
        ]);

        return redirect()->back()->with('success', "Tagihan {$tagihan->no_tagihan} telah ditandai lunas. Kwitansi: {$noKwitansi}");
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->back()->with('success', 'Tagihan berhasil dihapus.');
    }
}
