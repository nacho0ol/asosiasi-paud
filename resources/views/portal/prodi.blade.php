<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Prodi - SIMA PAUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .topbar { background: #1a3c5e; color: white; padding: 12px 20px; }
        .member-card { background: linear-gradient(135deg, #155724, #28a745); color: white; border-radius: 12px; padding: 25px; }
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
        <strong>{{ $globalSetting->singkatan ?? 'SIMA PAUD' }}</strong> <small class="opacity-75">Portal Program Studi</small>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('portal.prodi.profil') }}" class="text-white text-decoration-none"><i class="bi bi-gear"></i> Profil</a>
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
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center mb-3">
                <div class="card-body py-4">
                    <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px">
                        <i class="bi bi-building text-white" style="font-size:2rem"></i>
                    </div>
                    <h5 class="mb-1">{{ $prodi->nama_prodi }}</h5>
                    <p class="text-muted small mb-1">{{ $prodi->nama_universitas }}</p>
                    <p class="text-muted small">{{ $prodi->kota }}{{ $prodi->provinsi ? ', '.$prodi->provinsi : '' }}</p>
                    <a href="{{ route('portal.prodi.profil') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit Profil</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><td class="text-muted">Kaprodi</td><td>{{ $prodi->nama_kaprodi ?: '-' }}</td></tr>
                        <tr><td class="text-muted">Email</td><td>{{ $prodi->email }}</td></tr>
                        <tr><td class="text-muted">Telepon</td><td>{{ $prodi->telepon ?: '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @if($prodi->status_pendaftaran === 'pending')
            <div class="alert alert-warning">
                <i class="bi bi-hourglass-split fs-4 me-2"></i>
                <strong>Pendaftaran sedang diproses.</strong><br>
                Admin akan segera memverifikasi data Program Studi Anda. Piagam member akan diterbitkan setelah disetujui.
            </div>
            @elseif($prodi->status_pendaftaran === 'rejected')
            <div class="alert alert-danger">
                <i class="bi bi-x-circle fs-4 me-2"></i>
                <strong>Pendaftaran ditolak.</strong> Silakan hubungi sekretariat asosiasi.
            </div>
            @endif

            @if($member)
            <div class="member-card mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75 mb-1">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</div>
                        <h4 class="mb-0">PIAGAM KEANGGOTAAN</h4>
                        <div class="small opacity-75">Program Studi</div>
                    </div>
                    <span class="badge bg-{{ $member->status === 'aktif' ? 'light text-success' : 'danger' }} fs-6 px-3 py-2">
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
                
                <div class="mt-3 d-flex gap-2">
                    @if($member->status === 'aktif')
                    <a href="{{ route('portal.prodi.piagam') }}" class="btn btn-light" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak Piagam
                    </a>
                    @endif
                    
                    {{-- TOMBOL SAKTI PERPANJANG MIDTRANS UNTUK LEVEL PRODI --}}
                    <a href="{{ route('portal-prodi.perpanjang') }}" class="btn btn-warning fw-bold text-dark shadow-sm">
                        <i class="bi bi-credit-card-fill"></i> Perpanjang via Midtrans
                    </a>
                </div>
            </div>

            {{-- Tagihan --}}
            @if(isset($tagihans) && $tagihans->count() > 0)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text text-warning"></i> Tagihan</span>
                    @if($tagihans->where('status','belum_bayar')->count() > 0)
                    <span class="badge bg-danger">{{ $tagihans->where('status','belum_bayar')->count() }} belum lunas</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 align-middle">
                        <thead class="table-light"><tr><th>No Tagihan</th><th>Keterangan</th><th>Jumlah</th><th>Jatuh Tempo</th><th>Status / Aksi</th></tr></thead>
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
                                    {{-- TOMBOL BAYAR MIDTRANS DI TABEL PRODI --}}
                                    <span class="badge bg-warning text-dark mb-1 d-block" style="width: fit-content;">Belum Bayar</span>
                                    <a href="{{ route('pembayaran.bayar', $t->id) }}" class="btn btn-sm btn-primary" style="font-size: 0.75rem;">
                                        💳 Bayar Midtrans
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if($tagihans->where('status','belum_bayar')->count() > 0)
                <div class="card-footer bg-primary bg-opacity-10">
                    <i class="bi bi-info-circle text-primary"></i>
                    <small>Silakan klik tombol <b>Bayar Midtrans</b> pada tabel di atas. Pembayaran menggunakan QRIS/Transfer Bank akan langsung mengaktifkan piagam secara otomatis.</small>
                </div>
                @endif
            </div>
            @endif

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

            {{-- Tabel Daftar Dosen --}}
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <span><i class="bi bi-people text-primary"></i> Daftar Dosen Anggota</span>
                    <span class="badge bg-primary">{{ $dosens->count() }} Orang</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Nama Dosen</th>
                                <th>NIDN</th>
                                <th>Status Pendaftaran</th>
                                <th>Status Member</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dosens as $index => $d)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->nidn }}</td>
                                <td>
                                    @if($d->status_pendaftaran == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($d->status_pendaftaran == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($d->memberDosen)
                                        @if($d->memberDosen->status == 'aktif')
                                            <span class="badge bg-success">Aktif (s/d {{ $d->memberDosen->tanggal_berakhir->format('d/m/y') }})</span>
                                        @else
                                            <span class="badge bg-danger">Expired</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                    Belum ada dosen yang mendaftar di program studi ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-award text-muted" style="font-size:3rem"></i>
                    <p class="mt-3 text-muted">Piagam member belum diterbitkan.<br>Tunggu persetujuan dari admin.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>