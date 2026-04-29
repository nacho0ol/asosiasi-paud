<?php

namespace App\Http\Controllers;

use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Pembayaran;
use App\Models\Setting;

class VerifikasiController extends Controller
{
    public function show(string $tipe, string $kode)
    {
        $setting = Setting::first();
        $kode    = strtoupper($kode);

        switch ($tipe) {
            case 'kwitansi':
                return $this->verifikasiKwitansi($kode, $setting);

            case 'kartu':
                return $this->verifikasiKartuDosen($kode, $setting);

            case 'piagam':
                return $this->verifikasiPiagam($kode, $setting);

            case 'ttd-ketua':
                return $this->verifikasiTtd($kode, $setting, 'ketua');

            case 'ttd-bendahara':
                return $this->verifikasiTtd($kode, $setting, 'bendahara');

            default:
                return view('verifikasi.tidak-ditemukan', compact('setting', 'kode'));
        }
    }

    // backward compat untuk QR lama
    public function showLegacy(string $kode)
    {
        $setting = Setting::first();
        $kode    = strtoupper($kode);

        $pembayaran = Pembayaran::where('kode_verifikasi', $kode)->first();
        if ($pembayaran) return $this->verifikasiKwitansi($kode, $setting);

        $memberDosen = MemberDosen::where('kode_verifikasi', $kode)->first();
        if ($memberDosen) return $this->verifikasiKartuDosen($kode, $setting);

        $memberProdi = MemberProdi::where('kode_verifikasi', $kode)->first();
        if ($memberProdi) return $this->verifikasiPiagam($kode, $setting);

        return view('verifikasi.tidak-ditemukan', compact('setting', 'kode'));
    }

    private function verifikasiKwitansi(string $kode, $setting)
    {
        $pembayaran = Pembayaran::where('kode_verifikasi', $kode)->first();
        if (!$pembayaran) return view('verifikasi.tidak-ditemukan', compact('setting', 'kode'));

        if ($pembayaran->jenis === 'dosen') {
            $pembayaran->load('memberDosen.dosen.prodi');
        } else {
            $pembayaran->load('memberProdi.prodi');
        }
        $tipe = 'kwitansi';
        return view('verifikasi.kwitansi', compact('pembayaran', 'setting', 'kode', 'tipe'));
    }

    private function verifikasiKartuDosen(string $kode, $setting)
    {
        $memberDosen = MemberDosen::where('kode_verifikasi', $kode)->with('dosen.prodi')->first();
        if (!$memberDosen) return view('verifikasi.tidak-ditemukan', compact('setting', 'kode'));
        $tipe = 'kartu';
        return view('verifikasi.member-dosen', compact('memberDosen', 'setting', 'kode', 'tipe'));
    }

    private function verifikasiPiagam(string $kode, $setting)
    {
        $memberProdi = MemberProdi::where('kode_verifikasi', $kode)->with('prodi')->first();
        if (!$memberProdi) return view('verifikasi.tidak-ditemukan', compact('setting', 'kode'));
        $tipe = 'piagam';
        return view('verifikasi.member-prodi', compact('memberProdi', 'setting', 'kode', 'tipe'));
    }

    private function verifikasiTtd(string $kode, $setting, string $jabatan)
    {
        // Format kode: {kode_ttd_xxx}-{kode_verifikasi_dokumen}
        $parts       = explode('-', $kode, 2);
        $kodeTtd     = $parts[0] ?? '';
        $kodeDokumen = $parts[1] ?? '';

        // Validasi kode TTD milik setting
        $fieldKode = $jabatan === 'ketua' ? 'kode_ttd_ketua' : 'kode_ttd_bendahara';
        $fieldAt   = $jabatan === 'ketua' ? 'ttd_ketua_at' : 'ttd_bendahara_at';
        $fieldNama = $jabatan === 'ketua' ? 'nama_ketua' : 'nama_bendahara';

        $valid = $setting && strtoupper($setting->$fieldKode) === strtoupper($kodeTtd);

        // Cari dokumen terkait
        $dokumen = null;
        $tipeDokumen = null;

        if ($kodeDokumen) {
            $pembayaran = Pembayaran::where('kode_verifikasi', strtoupper($kodeDokumen))->first();
            if ($pembayaran) {
                $dokumen = $pembayaran;
                $tipeDokumen = 'kwitansi';
                if ($pembayaran->jenis === 'dosen') $pembayaran->load('memberDosen.dosen');
                else $pembayaran->load('memberProdi.prodi');
            }

            if (!$dokumen) {
                $memberDosen = MemberDosen::where('kode_verifikasi', strtoupper($kodeDokumen))->with('dosen')->first();
                if ($memberDosen) { $dokumen = $memberDosen; $tipeDokumen = 'kartu'; }
            }

            if (!$dokumen) {
                $memberProdi = MemberProdi::where('kode_verifikasi', strtoupper($kodeDokumen))->with('prodi')->first();
                if ($memberProdi) { $dokumen = $memberProdi; $tipeDokumen = 'piagam'; }
            }
        }

        $namaPenandatangan = $setting ? $setting->$fieldNama : null;
        $jabatanLabel      = $jabatan === 'ketua' ? 'Ketua Umum' : 'Bendahara';
        $tanggalTtd        = $setting ? $setting->$fieldAt : null;

        return view('verifikasi.ttd', compact(
            'setting', 'kode', 'valid', 'jabatan', 'jabatanLabel',
            'namaPenandatangan', 'tanggalTtd', 'dokumen', 'tipeDokumen'
        ));
    }
}
