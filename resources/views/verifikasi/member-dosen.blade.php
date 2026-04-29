<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi Kartu Member - {{ $setting->singkatan ?? 'SIMA PAUD' }}</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
body { background: linear-gradient(135deg, #1a3c5e 0%, #2d6a9f 100%); min-height: 100vh; display: flex; align-items: center; padding: 30px 0; }
.card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
.verified-badge { border-radius: 50px; padding: 8px 20px; font-size: 1rem; display: inline-flex; align-items: center; gap: 8px; }
</style>
</head>
<body>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-6">
    <div class="card">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                @if(!empty($setting->logo))
                <img src="{{ asset('storage/'.$setting->logo) }}" style="height:50px;object-fit:contain;" class="mb-2">
                @endif
                <h5 class="mb-1">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</h5>
                <div class="text-muted small">Sistem Verifikasi Dokumen Digital</div>
            </div>

            @php $isAktif = $memberDosen->status === 'aktif' && $memberDosen->tanggal_berakhir->isFuture(); @endphp

            <div class="text-center mb-4">
                @if($isAktif)
                <div class="verified-badge bg-success text-white mb-2">
                    <i class="bi bi-patch-check-fill"></i> Kartu Member AKTIF
                </div>
                @else
                <div class="verified-badge bg-danger text-white mb-2">
                    <i class="bi bi-x-circle-fill"></i> Kartu Member TIDAK AKTIF
                </div>
                @endif
                <div class="text-muted small">Kode: <strong>{{ $kode }}</strong></div>
            </div>

            <div class="alert alert-{{ $isAktif ? 'success' : 'warning' }} border-0">
                <h6 class="mb-3"><i class="bi bi-credit-card"></i> Kartu Anggota Dosen</h6>
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted" width="40%">No Anggota</td><td><strong>{{ $memberDosen->no_member }}</strong></td></tr>
                    <tr><td class="text-muted">Nama</td><td>{{ $memberDosen->dosen->nama }}</td></tr>
                    <tr><td class="text-muted">NIDN</td><td>{{ $memberDosen->dosen->nidn }}</td></tr>
                    <tr><td class="text-muted">Prodi</td><td>{{ $memberDosen->dosen->prodi->nama_prodi ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Universitas</td><td>{{ $memberDosen->dosen->prodi->nama_universitas ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Berlaku s/d</td><td>{{ $memberDosen->tanggal_berakhir->format('d F Y') }}
                        @if(!$isAktif)<span class="badge bg-danger ms-1">Expired</span>@endif
                    </td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        <span class="badge bg-{{ $isAktif ? 'success' : 'danger' }}">{{ strtoupper($memberDosen->status) }}</span>
                    </td></tr>
                </table>
            </div>

            <div class="text-center text-muted small mt-3">
                <i class="bi bi-shield-check text-success"></i>
                Dokumen ini diterbitkan secara resmi oleh {{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}
            </div>
        </div>
    </div>
</div>
</div>
</div>
</body>
</html>
