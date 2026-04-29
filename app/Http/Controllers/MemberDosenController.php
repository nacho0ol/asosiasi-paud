<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MemberDosen;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MemberDosenController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->q;
        $status = $request->status;
        $members = MemberDosen::with('dosen.prodi')
            ->when($q, fn($query) => $query
                ->where('no_member', 'like', "%$q%")
                ->orWhereHas('dosen', fn($d) => $d
                    ->where('nama', 'like', "%$q%")
                    ->orWhere('nidn', 'like', "%$q%")
                )
            )
            ->when($status, fn($query) => $query->where('status', $status))
            ->paginate(15)->withQueryString();
        return view('member-dosen.index', compact('members', 'q', 'status'));
    }

    public function create()
    {
        $dosens = Dosen::whereDoesntHave('memberDosen', function ($q) {
            $q->where('status', 'aktif');
        })->get();
        return view('member-dosen.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal_mulai' => 'required|date',
        ]);

        $mulai = Carbon::parse($request->tanggal_mulai);
        $berakhir = $mulai->copy()->addYear();

        $noMember = 'MD-' . date('Y') . '-' . str_pad(MemberDosen::count() + 1, 4, '0', STR_PAD_LEFT);

        MemberDosen::create([
            'dosen_id' => $request->dosen_id,
            'no_member' => $noMember,
            'tanggal_mulai' => $mulai,
            'tanggal_berakhir' => $berakhir,
            'status' => 'aktif',
        ]);

        return redirect()->route('member-dosen.index')->with('success', 'Member dosen berhasil ditambahkan.');
    }

    public function show(MemberDosen $memberDosen)
    {
        $memberDosen->load('dosen.prodi', 'pembayarans');
        return view('member-dosen.show', compact('memberDosen'));
    }

    public function edit(MemberDosen $memberDosen)
    {
        $dosens = Dosen::all();
        return view('member-dosen.edit', compact('memberDosen', 'dosens'));
    }

    public function update(Request $request, MemberDosen $memberDosen)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:aktif,tidak_aktif,expired',
        ]);

        $memberDosen->update($request->only('tanggal_mulai', 'tanggal_berakhir', 'status'));
        return redirect()->route('member-dosen.index')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(MemberDosen $memberDosen)
    {
        $memberDosen->delete();
        return redirect()->route('member-dosen.index')->with('success', 'Member berhasil dihapus.');
    }

    public function perpanjang(MemberDosen $memberDosen)
    {
        $berakhir = Carbon::parse($memberDosen->tanggal_berakhir)->addYear();
        $memberDosen->update([
            'tanggal_berakhir' => $berakhir,
            'status' => 'aktif',
            'notif_terkirim' => false,
        ]);
        return redirect()->back()->with('success', 'Member berhasil diperpanjang 1 tahun.');
    }
}
