<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Pembayaran;
use App\Models\Prodi;
use App\Models\Tagihan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDosen       = Dosen::count();
        $totalProdi       = Prodi::count();
        $memberDosenAktif = MemberDosen::where('status', 'aktif')->count();
        $memberProdiAktif = MemberProdi::where('status', 'aktif')->count();

        // Member akan expired dalam 30 hari
        $akanExpired = MemberDosen::where('status', 'aktif')
            ->whereBetween('tanggal_berakhir', [Carbon::today(), Carbon::today()->addDays(30)])
            ->with('dosen')
            ->get();

        $totalPendapatan = Pembayaran::sum('jumlah');

        // Tagihan belum lunas
        $tagihanBelumLunas = Tagihan::where('status', 'belum_bayar')->count();

        // Statistik anggota aktif per provinsi (dosen)
        $dosenPerWilayah = DB::table('member_dosens')
            ->join('dosens', 'member_dosens.dosen_id', '=', 'dosens.id')
            ->join('prodis', 'dosens.prodi_id', '=', 'prodis.id')
            ->where('member_dosens.status', 'aktif')
            ->whereNotNull('prodis.provinsi')
            ->where('prodis.provinsi', '!=', '')
            ->select('prodis.provinsi', DB::raw('count(*) as total'))
            ->groupBy('prodis.provinsi')
            ->orderByDesc('total')
            ->get();

        // Statistik member prodi aktif per provinsi
        $prodiPerWilayah = DB::table('member_prodis')
            ->join('prodis', 'member_prodis.prodi_id', '=', 'prodis.id')
            ->where('member_prodis.status', 'aktif')
            ->whereNotNull('prodis.provinsi')
            ->where('prodis.provinsi', '!=', '')
            ->select('prodis.provinsi', DB::raw('count(*) as total'))
            ->groupBy('prodis.provinsi')
            ->orderByDesc('total')
            ->get();

        // Gabungkan per wilayah
        $wilayah = collect();
        $allProvinsi = $dosenPerWilayah->pluck('provinsi')
            ->merge($prodiPerWilayah->pluck('provinsi'))
            ->unique()->sort()->values();

        foreach ($allProvinsi as $prov) {
            $wilayah->push([
                'provinsi'    => $prov,
                'dosen'       => $dosenPerWilayah->firstWhere('provinsi', $prov)?->total ?? 0,
                'prodi'       => $prodiPerWilayah->firstWhere('provinsi', $prov)?->total ?? 0,
            ]);
        }
        $wilayah = $wilayah->sortByDesc(fn($w) => $w['dosen'] + $w['prodi'])->values();

        return view('dashboard', compact(
            'totalDosen', 'totalProdi', 'memberDosenAktif', 'memberProdiAktif',
            'akanExpired', 'totalPendapatan', 'tagihanBelumLunas', 'wilayah'
        ));
    }
}
