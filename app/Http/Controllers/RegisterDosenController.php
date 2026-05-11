<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PendaftaranController;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterDosenController extends Controller
{
    public function showForm()
    {
        $prodis = Prodi::orderBy('nama_universitas')->get();
        return view('auth.register-dosen', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'nidn'               => 'required|string|unique:dosens,nidn',
            'email'              => 'required|email|unique:users,email|unique:dosens,email',
            'password'           => 'required|string|min:8|confirmed',
            'prodi_id'           => 'required|exists:prodis,id',
            'telepon'            => 'nullable|string|max:20',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'pendidikan_terakhir'=> 'nullable|string|max:10',
            'foto'               => 'nullable|image|max:2048',
        ]);

        // Buat user
        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'dosen',
        ]);

        // Simpan foto jika ada
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto-dosen', 'public');
        }

        // Buat data dosen dengan status pending
        $dosen = Dosen::create([
            'user_id'             => $user->id,
            'prodi_id'            => $request->prodi_id,
            'nama'                => $request->nama,
            'nidn'                => $request->nidn,
            'email'               => $request->email,
            'telepon'             => $request->telepon,
            'jabatan_fungsional'  => $request->jabatan_fungsional,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'foto'                => $foto,
            'status_pendaftaran'  => 'pending',
        ]);

        // Login otomatis
        Auth::login($user);

        // Buat tagihan otomatis
        PendaftaranController::buatTagihanDosen($dosen);

        return redirect()->route('portal.index')
            ->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin.');
    }
}
