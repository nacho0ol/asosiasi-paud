<?php

namespace App\Http\Controllers;

use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Pembayaran;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    private function imgPath(?string $storagePath): ?string
    {
        if (!$storagePath) return null;
        $full = storage_path('app/public/' . $storagePath);
        if (!file_exists($full)) return null;
        // Return absolute path — DomPDF will convert to file:// URI internally
        return realpath($full);
    }

    private function qrHtml(string $data, int $size = 3): string
    {
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_HTML,
            'eccLevel'   => QRCode::ECC_M,
            'scale'      => $size,
            'cssClass'   => 'qr-html',
        ]);
        return (new QRCode($options))->render($data);
    }

    private function verifikasiUrl(string $kode, string $tipe = 'dokumen'): string
    {
        return rtrim(config('app.url', 'http://localhost'), '/') . '/verifikasi/' . $tipe . '/' . $kode;
    }

    private function hashDokumen(array $data): string
    {
        return strtoupper(substr(hash('sha256', implode('|', $data)), 0, 16));
    }

    private function settingImgs(?Setting $setting): array
    {
        if (!$setting) {
            return ['logo' => null, 'ttd_ketua' => null, 'ttd_bendahara' => null, 'cap' => null];
        }
        return [
            'logo'          => $this->imgPath($setting->logo),
            'ttd_ketua'     => $this->imgPath($setting->ttd_ketua),
            'ttd_bendahara' => $this->imgPath($setting->ttd_bendahara),
            'cap'           => $this->imgPath($setting->cap),
        ];
    }

    public function kartuDosen(MemberDosen $memberDosen)
    {
        $memberDosen->load('dosen.prodi');
        $setting = Setting::first();

        if (empty($memberDosen->kode_verifikasi)) {
            $memberDosen->kode_verifikasi = strtoupper(Str::random(12));
        }
        if (empty($memberDosen->hash_dokumen)) {
            $memberDosen->hash_dokumen = $this->hashDokumen([
                $memberDosen->no_member,
                $memberDosen->dosen->nidn ?? '',
                $memberDosen->tanggal_berakhir,
            ]);
        }
        $memberDosen->saveQuietly();

        $qrDokumen   = $this->qrHtml($this->verifikasiUrl($memberDosen->kode_verifikasi, 'kartu'), 3);
        $qrKetua     = $setting && $setting->kode_ttd_ketua
            ? $this->qrHtml($this->verifikasiUrl($setting->kode_ttd_ketua . '-' . $memberDosen->kode_verifikasi, 'ttd-ketua'), 3)
            : null;
        $qrBendahara = $setting && $setting->kode_ttd_bendahara
            ? $this->qrHtml($this->verifikasiUrl($setting->kode_ttd_bendahara . '-' . $memberDosen->kode_verifikasi, 'ttd-bendahara'), 3)
            : null;
        $imgs        = $this->settingImgs($setting);

        $pdf = Pdf::loadView('pdf.kartu-dosen', compact('memberDosen', 'setting', 'qrDokumen', 'qrKetua', 'qrBendahara', 'imgs'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream('kartu-member-' . $memberDosen->no_member . '.pdf');
    }

    public function piagamProdi(MemberProdi $memberProdi)
    {
        $memberProdi->load('prodi');
        $setting = Setting::first();

        if (empty($memberProdi->kode_verifikasi)) {
            $memberProdi->kode_verifikasi = strtoupper(Str::random(12));
        }
        if (empty($memberProdi->hash_dokumen)) {
            $memberProdi->hash_dokumen = $this->hashDokumen([
                $memberProdi->no_member,
                $memberProdi->prodi->nama_prodi ?? '',
                $memberProdi->tanggal_berakhir,
            ]);
        }
        $memberProdi->saveQuietly();

        $qrKetua = $setting && $setting->kode_ttd_ketua
            ? $this->qrHtml($this->verifikasiUrl($setting->kode_ttd_ketua . '-' . $memberProdi->kode_verifikasi, 'ttd-ketua'), 3)
            : null;
        $qrBendahara = $setting && $setting->kode_ttd_bendahara
            ? $this->qrHtml($this->verifikasiUrl($setting->kode_ttd_bendahara . '-' . $memberProdi->kode_verifikasi, 'ttd-bendahara'), 3)
            : null;
        $qrDokumen = $this->qrHtml($this->verifikasiUrl($memberProdi->kode_verifikasi, 'piagam'), 3);
        $imgs      = $this->settingImgs($setting);

        $pdf = Pdf::loadView('pdf.piagam-prodi', compact('memberProdi', 'setting', 'qrKetua', 'qrBendahara', 'qrDokumen', 'imgs'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream('piagam-' . $memberProdi->no_member . '.pdf');
    }

    public function kwitansi(Pembayaran $pembayaran)
    {
        $setting = Setting::first();
        if ($pembayaran->jenis === 'dosen') {
            $pembayaran->load('memberDosen.dosen.prodi');
        } else {
            $pembayaran->load('memberProdi.prodi');
        }

        if (empty($pembayaran->kode_verifikasi)) {
            $pembayaran->kode_verifikasi = strtoupper(Str::random(12));
        }
        if (empty($pembayaran->hash_dokumen)) {
            $pembayaran->hash_dokumen = $this->hashDokumen([
                $pembayaran->no_kwitansi,
                $pembayaran->jumlah,
                $pembayaran->tanggal_bayar,
            ]);
        }
        $pembayaran->saveQuietly();

        $qrKetua = $setting && $setting->kode_ttd_ketua
            ? $this->qrHtml($this->verifikasiUrl($setting->kode_ttd_ketua . '-' . $pembayaran->kode_verifikasi, 'ttd-ketua'), 3)
            : null;
        $qrBendahara = $setting && $setting->kode_ttd_bendahara
            ? $this->qrHtml($this->verifikasiUrl($setting->kode_ttd_bendahara . '-' . $pembayaran->kode_verifikasi, 'ttd-bendahara'), 3)
            : null;
        $qrDokumen = $this->qrHtml($this->verifikasiUrl($pembayaran->kode_verifikasi, 'kwitansi'), 3);
        $imgs      = $this->settingImgs($setting);

        $pdf = Pdf::loadView('pdf.kwitansi', compact('pembayaran', 'setting', 'qrKetua', 'qrBendahara', 'qrDokumen', 'imgs'))
            ->setPaper('a5', 'landscape');
        return $pdf->stream('kwitansi-' . $pembayaran->no_kwitansi . '.pdf');
    }
}
