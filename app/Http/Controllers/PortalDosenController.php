<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class PortalDosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user   = auth()->user();
        $dosen  = $user->dosen;
        $setting = Setting::first();

        if (!$dosen) {
            return redirect()->route('login')->with('error', 'Data dosen tidak ditemukan.');
        }

        $member  = $dosen->memberDosen;
        $tagihans = Tagihan::where('jenis', 'dosen')->where('ref_id', $dosen->id)->orderBy('created_at', 'desc')->get();

        return view('portal.index', compact('dosen', 'member', 'setting', 'tagihans'));
    }

    public function editProfil()
    {
        $dosen = auth()->user()->dosen;
        return view('portal.profil', compact('dosen'));
    }

    public function updateProfil(Request $request)
    {
        $dosen = auth()->user()->dosen;

        $request->validate([
            'telepon'            => 'nullable|string|max:20',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'pendidikan_terakhir'=> 'nullable|string|max:10',
            'foto'               => 'nullable|image|max:2048',
        ]);

        $data = $request->only('telepon', 'jabatan_fungsional', 'pendidikan_terakhir');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-dosen', 'public');
        }

        $dosen->update($data);

        return redirect()->route('portal.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function perpanjang()
    {
        $user  = auth()->user();
        $dosen = $user->dosen; // Ubah pencarian menjadi dosen

        if (!$dosen) {
            return back()->with('error', 'Data dosen tidak valid.');
        }

        $tagihan = Tagihan::where('jenis', 'dosen') // Ubah jenis menjadi dosen
                          ->where('ref_id', $dosen->id)
                          ->where('status', 'belum_bayar')
                          ->first();

        
        $setting = Setting::first();
        $nominal = $setting ? $setting->iuran_dosen : 300000;

        if (!$tagihan) {
            $tagihan = Tagihan::create([
                'no_tagihan'  => 'INV-DSN-' . date('Ymd') . '-' . rand(1000, 9999),
                'jenis'       => 'dosen', 
                'ref_id'      => $dosen->id,
                'jumlah'      => $nominal, 
                'status'      => 'belum_bayar',
                'jatuh_tempo' => now()->addDays(7),
                'keterangan'  => 'Perpanjangan Keanggotaan Dosen (Auto)'
            ]);
        }

        return redirect()->route('pembayaran.bayar', $tagihan->id);
    }
}
