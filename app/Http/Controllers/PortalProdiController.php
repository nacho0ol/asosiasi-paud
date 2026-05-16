<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class PortalProdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user    = auth()->user();
        $prodi   = $user->prodi;
        $setting = Setting::first();

        if (!$prodi) {
            return redirect()->route('login')->with('error', 'Data prodi tidak ditemukan.');
        }

        $member   = $prodi->memberProdi;
        $tagihans = Tagihan::where('jenis', 'prodi')->where('ref_id', $prodi->id)->orderBy('created_at', 'desc')->get();

        $dosens = \App\Models\Dosen::with('memberDosen')->where('prodi_id', $prodi->id)->get();

        return view('portal.prodi', compact('prodi', 'member', 'setting', 'tagihans', 'dosens'));
    }

    public function editProfil()
    {
        $prodi = auth()->user()->prodi;
        return view('portal.prodi-profil', compact('prodi'));
    }

    public function updateProfil(Request $request)
    {
        $prodi = auth()->user()->prodi;

        $request->validate([
            'telepon'      => 'nullable|string|max:20',
            'kota'         => 'nullable|string|max:100',
            'provinsi'     => 'nullable|string|max:100',
            'nama_kaprodi' => 'nullable|string|max:255',
        ]);

        $prodi->update($request->only('telepon', 'kota', 'provinsi', 'nama_kaprodi'));

        return redirect()->route('portal.prodi.index')->with('success', 'Profil berhasil diperbarui.');
    }

    // ==========================================
    // FUNGSI AUTO-TAGIHAN MIDTRANS KHUSUS PRODI
    // ==========================================
    public function perpanjang()
    {
        $user  = auth()->user();
        $prodi = $user->prodi;

        if (!$prodi) {
            return back()->with('error', 'Data prodi tidak valid.');
        }

        // 1. Cek apakah prodi ini udah punya tagihan yang belum dibayar?
        $tagihan = Tagihan::where('jenis', 'prodi')
                          ->where('ref_id', $prodi->id)
                          ->where('status', 'belum_bayar')
                          ->first();

        // 2. Kalau belum ada, kita bikinin otomatis senilai Rp 500.000 (Sesuai DemoSeeder)
        if (!$tagihan) {
            $tagihan = Tagihan::create([
                'no_tagihan'  => 'INV-PRD-' . date('Ymd') . '-' . rand(1000, 9999),
                'jenis'       => 'prodi',
                'ref_id'      => $prodi->id,
                'jumlah'      => 500000, 
                'status'      => 'belum_bayar',
                'jatuh_tempo' => now()->addDays(7),
                'keterangan'  => 'Perpanjangan Keanggotaan Prodi (Auto)'
            ]);
        }

        // 3. Lempar langsung ke halaman kasir Midtrans
        return redirect()->route('pembayaran.bayar', $tagihan->id);
    }
}