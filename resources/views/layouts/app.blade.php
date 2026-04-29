<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMA PAUD') - Sistem Informasi Member Asosiasi PAUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1a3c5e; }
        .sidebar .nav-link { color: #cdd8e3; padding: 10px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #2d6a9f; color: #fff; border-radius: 6px; }
        .sidebar .nav-link i { margin-right: 8px; }
        .sidebar-brand { color: #fff; font-weight: bold; font-size: 1.1rem; padding: 20px; border-bottom: 1px solid #2d6a9f; }
        .main-content { padding: 20px; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .badge-expired { background: #dc3545; }
        .badge-aktif { background: #198754; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-0">
            <div class="sidebar-brand">
                @if(!empty($globalSetting->logo))
                <img src="{{ asset('storage/'.$globalSetting->logo) }}" style="height:36px;object-fit:contain;margin-right:8px;vertical-align:middle;filter:brightness(0) invert(1);">
                @else
                <i class="bi bi-mortarboard-fill"></i>
                @endif
                {{ $globalSetting->singkatan ?? 'SIMA PAUD' }}
            </div>
            <nav class="nav flex-column mt-2">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <div class="px-3 py-1 text-uppercase text-muted small mt-2" style="font-size:.7rem">Data Master</div>
                <a class="nav-link {{ request()->is('prodi*') ? 'active' : '' }}" href="{{ route('prodi.index') }}">
                    <i class="bi bi-building"></i> Program Studi
                </a>
                <a class="nav-link {{ request()->is('dosen*') ? 'active' : '' }}" href="{{ route('dosen.index') }}">
                    <i class="bi bi-person-badge"></i> Dosen
                </a>
                <div class="px-3 py-1 text-uppercase text-muted small mt-2" style="font-size:.7rem">Keanggotaan</div>
                <a class="nav-link {{ request()->is('pendaftaran*') ? 'active' : '' }}" href="{{ route('pendaftaran.index') }}">
                    <i class="bi bi-person-plus"></i> Pendaftaran
                    @php $pendingCount = \App\Models\Dosen::where('status_pendaftaran','pending')->count(); @endphp
                    @if($pendingCount > 0)<span class="badge bg-warning text-dark float-end">{{ $pendingCount }}</span>@endif
                </a>
                <a class="nav-link {{ request()->is('member-dosen*') ? 'active' : '' }}" href="{{ route('member-dosen.index') }}">
                    <i class="bi bi-card-checklist"></i> Member Dosen
                </a>
                <a class="nav-link {{ request()->is('member-prodi*') ? 'active' : '' }}" href="{{ route('member-prodi.index') }}">
                    <i class="bi bi-award"></i> Member Prodi
                </a>
                <div class="px-3 py-1 text-uppercase text-muted small mt-2" style="font-size:.7rem">Keuangan</div>
                <a class="nav-link {{ request()->is('tagihan*') ? 'active' : '' }}" href="{{ route('tagihan.index') }}">
                    <i class="bi bi-file-earmark-text"></i> Tagihan
                    @php $tagihanCount = \App\Models\Tagihan::where('status','belum_bayar')->count(); @endphp
                    @if($tagihanCount > 0)<span class="badge bg-danger float-end">{{ $tagihanCount }}</span>@endif
                </a>
                <a class="nav-link {{ request()->is('pembayaran*') ? 'active' : '' }}" href="{{ route('pembayaran.index') }}">
                    <i class="bi bi-receipt"></i> Pembayaran
                </a>
                <div class="px-3 py-1 text-uppercase text-muted small mt-2" style="font-size:.7rem">Sistem</div>
                <a class="nav-link {{ request()->is('setting*') ? 'active' : '' }}" href="{{ route('setting.index') }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </nav>
        </div>
        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-muted">@yield('title', 'Dashboard')</h5>
                <small class="text-muted">{{ Auth::user()->name }}</small>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
