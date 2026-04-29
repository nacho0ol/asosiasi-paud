<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $dosens = Dosen::with('prodi', 'memberDosen')
            ->when($q, fn($query) => $query
                ->where('nama', 'like', "%$q%")
                ->orWhere('nidn', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('jabatan_fungsional', 'like', "%$q%")
                ->orWhereHas('prodi', fn($p) => $p->where('nama_universitas', 'like', "%$q%"))
            )
            ->paginate(15)->withQueryString();
        return view('dosen.index', compact('dosens', 'q'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('dosen.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nama' => 'required|string|max:255',
            'nidn' => 'required|string|unique:dosens,nidn',
            'email' => 'required|email|unique:dosens,email',
            'telepon' => 'nullable|string|max:20',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-dosen', 'public');
        }

        Dosen::create($data);
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen)
    {
        $dosen->load('prodi', 'memberDosen');
        return view('dosen.show', compact('dosen'));
    }

    public function edit(Dosen $dosen)
    {
        $prodis = Prodi::all();
        return view('dosen.edit', compact('dosen', 'prodis'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nama' => 'required|string|max:255',
            'nidn' => 'required|string|unique:dosens,nidn,' . $dosen->id,
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-dosen', 'public');
        }

        $dosen->update($data);
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}
