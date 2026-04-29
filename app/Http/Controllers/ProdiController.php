<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $prodis = Prodi::withCount('dosens')
            ->when($q, fn($query) => $query
                ->where('nama_prodi', 'like', "%$q%")
                ->orWhere('nama_universitas', 'like', "%$q%")
                ->orWhere('kota', 'like', "%$q%")
                ->orWhere('provinsi', 'like', "%$q%")
                ->orWhere('nama_kaprodi', 'like', "%$q%")
            )
            ->paginate(15)->withQueryString();
        return view('prodi.index', compact('prodis', 'q'));
    }

    public function create()
    {
        return view('prodi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'nama_universitas' => 'required|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'nama_kaprodi' => 'nullable|string|max:255',
        ]);

        Prodi::create($request->all());
        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function show(Prodi $prodi)
    {
        $prodi->load('dosens', 'memberProdi');
        return view('prodi.show', compact('prodi'));
    }

    public function edit(Prodi $prodi)
    {
        return view('prodi.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'nama_universitas' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $prodi->update($request->all());
        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil dihapus.');
    }
}
