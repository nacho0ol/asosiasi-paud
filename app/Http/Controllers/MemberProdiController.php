<?php

namespace App\Http\Controllers;

use App\Models\MemberProdi;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MemberProdiController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->q;
        $status = $request->status;
        $members = MemberProdi::with('prodi')
            ->when($q, fn($query) => $query
                ->where('no_member', 'like', "%$q%")
                ->orWhereHas('prodi', fn($p) => $p
                    ->where('nama_prodi', 'like', "%$q%")
                    ->orWhere('nama_universitas', 'like', "%$q%")
                )
            )
            ->when($status, fn($query) => $query->where('status', $status))
            ->paginate(15)->withQueryString();
        return view('member-prodi.index', compact('members', 'q', 'status'));
    }

    public function create()
    {
        $prodis = Prodi::whereDoesntHave('memberProdi', function ($q) {
            $q->where('status', 'aktif');
        })->get();
        return view('member-prodi.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'tanggal_mulai' => 'required|date',
        ]);

        $mulai = Carbon::parse($request->tanggal_mulai);
        $berakhir = $mulai->copy()->addYear();
        $noMember = 'MP-' . date('Y') . '-' . str_pad(MemberProdi::count() + 1, 4, '0', STR_PAD_LEFT);

        MemberProdi::create([
            'prodi_id' => $request->prodi_id,
            'no_member' => $noMember,
            'tanggal_mulai' => $mulai,
            'tanggal_berakhir' => $berakhir,
            'status' => 'aktif',
        ]);

        return redirect()->route('member-prodi.index')->with('success', 'Member prodi berhasil ditambahkan.');
    }

    public function show(MemberProdi $memberProdi)
    {
        $memberProdi->load('prodi', 'pembayarans');
        return view('member-prodi.show', compact('memberProdi'));
    }

    public function edit(MemberProdi $memberProdi)
    {
        $prodis = Prodi::all();
        return view('member-prodi.edit', compact('memberProdi', 'prodis'));
    }

    public function update(Request $request, MemberProdi $memberProdi)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,tidak_aktif,expired',
        ]);

        $memberProdi->update($request->only('tanggal_mulai', 'tanggal_berakhir', 'status'));
        return redirect()->route('member-prodi.index')->with('success', 'Member prodi berhasil diperbarui.');
    }

    public function destroy(MemberProdi $memberProdi)
    {
        $memberProdi->delete();
        return redirect()->route('member-prodi.index')->with('success', 'Member prodi berhasil dihapus.');
    }

    public function perpanjang(MemberProdi $memberProdi)
    {
        $berakhir = Carbon::parse($memberProdi->tanggal_berakhir)->addYear();
        $memberProdi->update([
            'tanggal_berakhir' => $berakhir,
            'status' => 'aktif',
        ]);
        return redirect()->back()->with('success', 'Member prodi berhasil diperpanjang 1 tahun.');
    }
}
