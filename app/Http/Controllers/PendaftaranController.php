<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Prodi;
use App\Models\Setting;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendingDosen  = Dosen::where('status_pendaftaran', 'pending')->with('prodi')->latest()->get();
        $pendingProdi  = Prodi::where('status_pendaftaran', 'pending')->latest()->get();
        $approvedDosen = Dosen::where('status_pendaftaran', 'approved')->with('prodi', 'memberDosen')->latest()->paginate(10, ['*'], 'page_dosen');
        $approvedProdi = Prodi::where('status_pendaftaran', 'approved')->with('memberProdi')->latest()->paginate(10, ['*'], 'page_prodi');

        return view('pendaftaran.index', compact('pendingDosen', 'pendingProdi', 'approvedDosen', 'approvedProdi'));
    }

    public function approveDosen(Dosen $dosen)
    {
        $dosen->update(['status_pendaftaran' => 'approved']);

        $noMember = 'MD-' . date('Y') . '-' . str_pad(MemberDosen::count() + 1, 4, '0', STR_PAD_LEFT);
        MemberDosen::create([
            'dosen_id'         => $dosen->id,
            'no_member'        => $noMember,
            'tanggal_mulai'    => Carbon::today(),
            'tanggal_berakhir' => Carbon::today()->addYear(4),
            'status'           => 'aktif',
        ]);

        // Tandai tagihan pending jadi aktif (jatuh tempo 7 hari)
        $tagihan = Tagihan::where('jenis', 'dosen')->where('ref_id', $dosen->id)->where('status', 'belum_bayar')->first();
        if (!$tagihan) {
            self::buatTagihanDosen($dosen);
        }

        return redirect()->back()->with('success', "Pendaftaran {$dosen->nama} disetujui. No Member: {$noMember}");
    }

    public function rejectDosen(Dosen $dosen)
    {
        $dosen->update(['status_pendaftaran' => 'rejected']);
        Tagihan::where('jenis', 'dosen')->where('ref_id', $dosen->id)->delete();
        return redirect()->back()->with('success', "Pendaftaran dosen {$dosen->nama} ditolak.");
    }

    public function approveProdi(Prodi $prodi)
    {
        $prodi->update(['status_pendaftaran' => 'approved']);

        $noMember = 'MP-' . date('Y') . '-' . str_pad(MemberProdi::count() + 1, 4, '0', STR_PAD_LEFT);
        MemberProdi::create([
            'prodi_id'         => $prodi->id,
            'no_member'        => $noMember,
            'tanggal_mulai'    => Carbon::today(),
            'tanggal_berakhir' => Carbon::today()->addYear(),
            'status'           => 'aktif',
        ]);

        $tagihan = Tagihan::where('jenis', 'prodi')->where('ref_id', $prodi->id)->where('status', 'belum_bayar')->first();
        if (!$tagihan) {
            self::buatTagihanProdi($prodi);
        }

        return redirect()->back()->with('success', "Pendaftaran prodi {$prodi->nama_prodi} disetujui. No Member: {$noMember}");
    }

    public function rejectProdi(Prodi $prodi)
    {
        $prodi->update(['status_pendaftaran' => 'rejected']);
        Tagihan::where('jenis', 'prodi')->where('ref_id', $prodi->id)->delete();
        return redirect()->back()->with('success', "Pendaftaran prodi {$prodi->nama_prodi} ditolak.");
    }

    // Helper: buat tagihan dosen
    public static function buatTagihanDosen(Dosen $dosen)
    {
        $setting = Setting::first();
        $jumlah  = $setting->iuran_dosen ?? 300000;
        $no      = 'TGH-D-' . date('Ymd') . '-' . str_pad(Tagihan::count() + 1, 4, '0', STR_PAD_LEFT);

        return Tagihan::create([
            'no_tagihan'  => $no,
            'jenis'       => 'dosen',
            'ref_id'      => $dosen->id,
            'jumlah'      => $jumlah,
            'jatuh_tempo' => Carbon::today()->addDays(14),
            'status'      => 'belum_bayar',
            'keterangan'  => 'Iuran Keanggotaan Tahunan Dosen',
        ]);
    }

    // Helper: buat tagihan prodi
    public static function buatTagihanProdi(Prodi $prodi)
    {
        $setting = Setting::first();
        $jumlah  = $setting->iuran_prodi ?? 500000;
        $no      = 'TGH-P-' . date('Ymd') . '-' . str_pad(Tagihan::count() + 1, 4, '0', STR_PAD_LEFT);

        return Tagihan::create([
            'no_tagihan'  => $no,
            'jenis'       => 'prodi',
            'ref_id'      => $prodi->id,
            'jumlah'      => $jumlah,
            'jatuh_tempo' => Carbon::today()->addDays(14),
            'status'      => 'belum_bayar',
            'keterangan'  => 'Iuran Keanggotaan Tahunan Program Studi',
        ]);
    }

    // backward compat
    public function approve(Dosen $dosen) { return $this->approveDosen($dosen); }
    public function reject(Dosen $dosen)  { return $this->rejectDosen($dosen); }
}
