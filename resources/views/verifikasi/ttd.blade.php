<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi Tanda Tangan Digital</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
body { background: #1a3c5e; min-height: 100vh; display: flex; align-items: center; padding: 30px 0; }
.card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.4); }
.badge-valid { background: #198754; color: white; border-radius: 50px; padding: 10px 24px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px; }
.badge-invalid { background: #dc3545; color: white; border-radius: 50px; padding: 10px 24px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px; }
.signer-card { background: linear-gradient(135deg, #1a3c5e, #2d6a9f); color: white; border-radius: 12px; padding: 20px; }
.hash-box { font-family: monospace; font-size: 11px; background: #f8f9fa; padding: 8px 12px; border-radius: 6px; word-break: break-all; color: #555; border: 1px solid #dee2e6; }
.btn-lihat { background: #dc3545; color: white; border: none; border-radius: 8px; padding: 10px 24px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
.btn-lihat:hover { background: #b02a37; color: white; }
.divider { border-top: 1px solid #e9ecef; margin: 16px 0; }
</style>
</head>
<body>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-6">
    <div class="card">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="text-center mb-4">
                @if(!empty($setting->logo))
                <img src="{{ asset('storage/'.$setting->logo) }}" style="height:55px;object-fit:contain;" class="mb-2">
                @endif
                <h5 class="mb-0">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</h5>
                <div class="text-muted small">Sistem Verifikasi Tanda Tangan Digital</div>
            </div>

            {{-- Status --}}
            <div class="text-center mb-4">
                @if($valid)
                <div class="badge-valid mb-2">
                    <i class="bi bi-patch-check-fill"></i> Tanda Tangan Terverifikasi
                </div>
                @else
                <div class="badge-invalid mb-2">
                    <i class="bi bi-shield-x"></i> Tanda Tangan Tidak Valid
                </div>
                @endif
            </div>

            @if($valid)
            {{-- Info Penandatangan --}}
            <div class="signer-card mb-4">
                <div class="small opacity-75 mb-1">Dokumen ini telah ditandatangani oleh:</div>
                <div class="row mt-2">
                    <div class="col-6">
                        <div class="small opacity-75">Nama</div>
                        <div class="fw-bold">{{ $namaPenandatangan ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small opacity-75">Jabatan</div>
                        <div class="fw-bold">{{ $jabatanLabel }}</div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="small opacity-75">Organisasi</div>
                        <div class="fw-bold" style="font-size:13px">{{ $setting->singkatan ?? '' }}</div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="small opacity-75">Ditandatangani pada</div>
                        <div class="fw-bold" style="font-size:13px">
                            {{ $tanggalTtd ? $tanggalTtd->translatedFormat('l, d F Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Dokumen --}}
            @if($dokumen)
            <div class="divider"></div>
            <h6 class="mb-3"><i class="bi bi-file-earmark-text text-primary"></i> Dokumen Terkait</h6>
            <table class="table table-sm">
                @if($tipeDokumen === 'kwitansi')
                <tr><td class="text-muted" width="40%">Jenis</td><td>Kwitansi Pembayaran</td></tr>
                <tr><td class="text-muted">No Kwitansi</td><td><strong>{{ $dokumen->no_kwitansi }}</strong></td></tr>
                <tr><td class="text-muted">Tanggal</td><td>{{ $dokumen->tanggal_bayar->format('d F Y') }}</td></tr>
                <tr><td class="text-muted">Jumlah</td><td><strong>Rp {{ number_format($dokumen->jumlah, 0, ',', '.') }}</strong></td></tr>
                @elseif($tipeDokumen === 'kartu')
                <tr><td class="text-muted" width="40%">Jenis</td><td>Kartu Anggota Dosen</td></tr>
                <tr><td class="text-muted">No Anggota</td><td><strong>{{ $dokumen->no_member }}</strong></td></tr>
                <tr><td class="text-muted">Nama</td><td>{{ $dokumen->dosen->nama ?? '-' }}</td></tr>
                <tr><td class="text-muted">Berlaku s/d</td><td>{{ $dokumen->tanggal_berakhir->format('d F Y') }}</td></tr>
                @elseif($tipeDokumen === 'piagam')
                <tr><td class="text-muted" width="40%">Jenis</td><td>Piagam Keanggotaan Prodi</td></tr>
                <tr><td class="text-muted">No Anggota</td><td><strong>{{ $dokumen->no_member }}</strong></td></tr>
                <tr><td class="text-muted">Prodi</td><td>{{ $dokumen->prodi->nama_prodi ?? '-' }}</td></tr>
                <tr><td class="text-muted">Berlaku s/d</td><td>{{ $dokumen->tanggal_berakhir->format('d F Y') }}</td></tr>
                @endif
                @if(!empty($dokumen->hash_dokumen))
                <tr><td class="text-muted">Nilai Hash</td><td><span class="hash-box">{{ $dokumen->hash_dokumen }}</span></td></tr>
                @endif
            </table>

            {{-- Tombol Lihat File Digital --}}
            <div class="text-center mt-3">
                @if($tipeDokumen === 'kwitansi')
                <a href="{{ route('pdf.kwitansi', $dokumen) }}" class="btn-lihat" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Lihat File Digital
                </a>
                @elseif($tipeDokumen === 'kartu')
                <a href="{{ route('pdf.kartu-dosen', $dokumen) }}" class="btn-lihat" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Lihat File Digital
                </a>
                @elseif($tipeDokumen === 'piagam')
                <a href="{{ route('pdf.piagam-prodi', $dokumen) }}" class="btn-lihat" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Lihat File Digital
                </a>
                @endif
            </div>
            @endif

            @else
            {{-- Tidak valid --}}
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                Kode tanda tangan tidak ditemukan atau tidak valid. Dokumen ini mungkin dipalsukan.
            </div>
            @endif

            <div class="divider"></div>
            <div class="text-center text-muted small">
                <i class="bi bi-shield-check text-success"></i>
                {{ $setting->nama_asosiasi ?? '' }} &bull; {{ $setting->website ?? '' }}
            </div>
        </div>
    </div>
</div>
</div>
</div>
</body>
</html>
