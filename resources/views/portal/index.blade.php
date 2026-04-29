<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Anggota - SIMA PAUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .topbar { background: #1a3c5e; color: white; padding: 12px 20px; }
        .member-card { background: linear-gradient(135deg, #1a3c5e, #2d6a9f); color: white; border-radius: 12px; padding: 25px; }
        .status-badge { font-size: 1rem; padding: 6px 16px; border-radius: 20px; }
    </style>
</head>
<body>
<div class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        @if(!empty($globalSetting->logo))
        <img src="{{ asset('storage/'.$globalSetting->logo) }}" style="height:32px;object-fit:contain;filter:brightness(0) invert(1);">
        @else
        <i class="bi bi-mortarboard-fill me-1"></i>
        @endif
        <strong>{{ $globalSetting->singkatan ?? 'SIMA PAUD' }}</strong> <small class="opacity-75">Portal Anggota</small>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('portal.profil') }}" class="text-white text-decoration-none"><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-right"></i> Keluar</button>
        </form>
    </div>
</div>

<div class="container py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            {{-- Foto & Info Dosen --}}
            <div class="card text-center mb-3">
                <div class="card-body py-4">
                    @if($dosen->foto)
                        <img src="{{ asset('storage/'.$dosen->foto) }}" class="rounded-circle mb-3" width="90" height="90" style="object-fit:cover">
                    @else
                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px">
                            <i class="bi bi-person-fill text-white" style="font-size:2.5rem"></i>
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $dosen->nama }}</h5>
                    <p class="text-muted small mb-1">{{ $dosen->jabatan_fungsional }}</p>
                    <p class="text-muted small">{{ $dosen->prodi->nama_prodi ?? '' }} - {{ $dosen->prodi->nama_universitas ?? '' }}</p>
                    <a href="{{ route('portal.profil') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit Profil</a>
                </div>
            </div>

            {{-- Info NIDN --}}
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><td class="text-muted">NIDN</td><td>{{ $dosen->nidn }}</td></tr>
                        <tr><td class="text-muted">Email</td><td>{{ $dosen->email }}</td></tr>
                        <tr><td class="text-muted">Telepon</td><td>{{ $dosen->telepon ?: '-' }}</td></tr>
                        <tr><td class="text-muted">Pendidikan</td><td>{{ $dosen->pendidikan_terakhir ?: '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Status Pendaftaran --}}
            @if($dosen->status_pendaftaran === 'pending')
            <div class="alert alert-warning">
                <i class="bi bi-hourglass-split fs-4 me-2"></i>
                <strong>Pendaftaran Anda sedang diproses.</strong><br>
                Admin akan segera memverifikasi data Anda. Kartu member akan diterbitkan setelah disetujui.
            </div>
            @elseif($dosen->status_pendaftaran === 'rejected')
            <div class="alert alert-danger">
                <i class="bi bi-x-circle fs-4 me-2"></i>
                <strong>Pendaftaran Anda ditolak.</strong><br>
                Silakan hubungi sekretariat asosiasi untuk informasi lebih lanjut.
            </div>
            @endif

            {{-- Kartu Member --}}
            @if($member)
            <div class="member-card mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75 mb-1">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</div>
                        <h4 class="mb-0">KARTU ANGGOTA</h4>
                    </div>
                    <span class="badge bg-{{ $member->status === 'aktif' ? 'success' : 'danger' }} status-badge">
                        {{ strtoupper($member->status) }}
                    </span>
                </div>
                <hr class="border-white opacity-25">
                <div class="row">
                    <div class="col-6">
                        <div class="small opacity-75">No. Anggota</div>
                        <div class="fs-5 fw-bold">{{ $member->no_member }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small opacity-75">Berlaku Hingga</div>
                        <div class="fs-5 fw-bold">{{ $member->tanggal_berakhir->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    @if($member->status === 'aktif')
                        @php $sisaHari = now()->diffInDays($member->tanggal_berakhir, false); @endphp
                        @if($sisaHari <= 30 && $sisaHari >= 0)
                        <div class="alert alert-warning py-2 mb-2">
                            <i class="bi bi-exclamation-triangle"></i> Masa berlaku tinggal <strong>{{ $sisaHari }} hari</strong>. Segera hubungi admin untuk perpanjangan.
                        </div>
                        @endif
                        <a href="{{ route('portal.kartu') }}" class="btn btn-light" target="_blank">
                            <i class="bi bi-credit-card"></i> Cetak Kartu Member
                        </a>
                    @else
                        <span class="text-white opacity-75 small">Kartu tidak dapat dicetak karena status tidak aktif.</span>
                    @endif
                </div>
            </div>

            {{-- Tagihan --}}
            @if($tagihans->count() > 0)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text text-warning"></i> Tagihan</span>
                    @if($tagihans->where('status','belum_bayar')->count() > 0)
                    <span class="badge bg-danger">{{ $tagihans->where('status','belum_bayar')->count() }} belum lunas</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>No Tagihan</th><th>Keterangan</th><th>Jumlah</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($tagihans as $t)
                        <tr>
                            <td><small>{{ $t->no_tagihan }}</small></td>
                            <td>{{ $t->keterangan }}</td>
                            <td><strong>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</strong></td>
                            <td>
                                {{ $t->jatuh_tempo->format('d/m/Y') }}
                                @if($t->status === 'belum_bayar' && $t->jatuh_tempo->isPast())
                                <span class="badge bg-danger ms-1">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                @if($t->status === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                                @else
                                <span class="badge bg-warning text-dark">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($tagihans->where('status','belum_bayar')->count() > 0)
                <div class="card-footer bg-warning bg-opacity-10">
                    <i class="bi bi-info-circle text-warning"></i>
                    <small>Silakan lakukan pembayaran dan konfirmasi ke sekretariat asosiasi. Kartu member aktif setelah pembayaran dikonfirmasi admin.</small>
                </div>
                @endif
            </div>
            @endif

            {{-- Riwayat Pembayaran --}}
            <div class="card">
                <div class="card-header"><i class="bi bi-receipt"></i> Riwayat Pembayaran</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>No Kwitansi</th><th>Tanggal</th><th>Jumlah</th><th>Metode</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($member->pembayarans as $p)
                        <tr>
                            <td>{{ $p->no_kwitansi }}</td>
                            <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($p->metode) }}</td>
                            <td>
                                <a href="{{ route('portal.kwitansi', $p) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Kwitansi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat pembayaran</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-card-checklist text-muted" style="font-size:3rem"></i>
                    <p class="mt-3 text-muted">Kartu member belum diterbitkan.<br>Tunggu persetujuan dari admin.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
