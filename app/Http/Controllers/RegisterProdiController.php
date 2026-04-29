<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterProdiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi'       => 'required|string|max:255',
            'nama_universitas' => 'required|string|max:255',
            'kota'             => 'nullable|string|max:100',
            'provinsi'         => 'nullable|string|max:100',
            'nama_kaprodi'     => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email|unique:prodis,email',
            'telepon'          => 'nullable|string|max:20',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->nama_kaprodi . ' (' . $request->nama_prodi . ')',
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'prodi',
        ]);

        Prodi::create([
            'user_id'            => $user->id,
            'nama_prodi'         => $request->nama_prodi,
            'nama_universitas'   => $request->nama_universitas,
            'kota'               => $request->kota,
            'provinsi'           => $request->provinsi,
            'nama_kaprodi'       => $request->nama_kaprodi,
            'email'              => $request->email,
            'telepon'            => $request->telepon,
            'status_pendaftaran' => 'pending',
        ]);

        Auth::login($user);

        // Buat tagihan otomatis
        PendaftaranController::buatTagihanProdi($prodi);

        return redirect()->route('portal.prodi.index')
            ->with('success', 'Pendaftaran Program Studi berhasil! Menunggu persetujuan admin.');
    }
}
