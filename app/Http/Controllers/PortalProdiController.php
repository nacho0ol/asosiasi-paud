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

        return view('portal.prodi', compact('prodi', 'member', 'setting', 'tagihans'));
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
}
