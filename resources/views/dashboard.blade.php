@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div><div class="fs-2 fw-bold">{{ $totalDosen }}</div><div>Total Dosen</div></div>
                <i class="bi bi-person-badge fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div><div class="fs-2 fw-bold">{{ $memberDosenAktif }}</div><div>Member Dosen Aktif</div></div>
                <i class="bi bi-card-checklist fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div><div class="fs-2 fw-bold">{{ $memberProdiAktif }}</div><div>Member Prodi Aktif</div></div>
                <i class="bi bi-building fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div><div class="fs-2 fw-bold">Rp {{ number_format($totalPendapatan/1000, 0, ',', '.') }}rb</div><div>Total Pendapatan</div></div>
                <i class="bi bi-cash-stack fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Tagihan belum lunas --}}
@if($tagihanBelumLunas > 0)
<div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <span>Ada <strong>{{ $tagihanBelumLunas }}</strong> tagihan yang belum lunas.
        <a href="{{ route('pembayaran.index') }}" class="alert-link">Lihat pembayaran</a>
    </span>
</div>
@endif

{{-- Member akan expired --}}
@if($akanExpired->count() > 0)
<div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-clock-history fs-5"></i>
    <span><strong>{{ $akanExpired->count() }}</strong> member dosen akan berakhir dalam 30 hari ke depan.</span>
</div>
<div class="card mb-4">
    <div class="card-header bg-danger text-white"><i class="bi bi-clock-history"></i> Member Akan Expired</div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light"><tr><th>No Member</th><th>Nama Dosen</th><th>Prodi</th><th>Berakhir</th><th>Sisa</th><th></th></tr></thead>
            <tbody>
            @foreach($akanExpired as $m)
            <tr>
                <td>{{ $m->no_member }}</td>
                <td>{{ $m->dosen->nama }}</td>
                <td>{{ $m->dosen->prodi->nama_prodi ?? '-' }}</td>
                <td>{{ $m->tanggal_berakhir->format('d/m/Y') }}</td>
                <td><span class="badge bg-danger">{{ now()->diffInDays($m->tanggal_berakhir) }} hari</span></td>
                <td>
                    <form action="{{ route('member-dosen.perpanjang', $m) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Perpanjang</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="row g-3">
    {{-- Aksi Cepat --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-lightning-fill text-warning"></i> Aksi Cepat</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('member-dosen.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Member Dosen</a>
                <a href="{{ route('member-prodi.create') }}" class="btn btn-info text-white"><i class="bi bi-plus-circle"></i> Tambah Member Prodi</a>
                <a href="{{ route('pembayaran.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Catat Pembayaran</a>
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-warning">
                    <i class="bi bi-person-plus"></i> Kelola Pendaftaran
                    @php $pending = \App\Models\Dosen::where('status_pendaftaran','pending')->count() + \App\Models\Prodi::where('status_pendaftaran','pending')->count(); @endphp
                    @if($pending > 0)<span class="badge bg-dark ms-1">{{ $pending }}</span>@endif
                </a>
            </div>
        </div>
    </div>

    {{-- Statistik Wilayah --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-geo-alt-fill text-danger"></i> Anggota Aktif per Wilayah</span>
                <small class="text-muted">{{ $wilayah->count() }} provinsi</small>
            </div>
            <div class="card-body p-0" style="max-height:380px;overflow-y:auto">
                @if($wilayah->count() > 0)
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>#</th>
                            <th>Provinsi</th>
                            <th class="text-center">Dosen</th>
                            <th class="text-center">Prodi</th>
                            <th>Grafik</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $maxTotal = $wilayah->max(fn($w) => $w['dosen'] + $w['prodi']); @endphp
                    @foreach($wilayah as $i => $w)
                    @php $total = $w['dosen'] + $w['prodi']; $pct = $maxTotal > 0 ? round($total/$maxTotal*100) : 0; @endphp
                    <tr>
                        <td class="text-muted">{{ $i+1 }}</td>
                        <td><strong>{{ $w['provinsi'] }}</strong></td>
                        <td class="text-center"><span class="badge bg-primary">{{ $w['dosen'] }}</span></td>
                        <td class="text-center"><span class="badge bg-info">{{ $w['prodi'] }}</span></td>
                        <td style="min-width:120px">
                            <div class="d-flex gap-1 align-items-center">
                                <div style="width:{{ $pct }}%;min-width:4px;height:14px;background:linear-gradient(90deg,#1a3c5e,#2d6a9f);border-radius:3px"></div>
                                <small class="text-muted">{{ $total }}</small>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                    Belum ada data wilayah. Pastikan data provinsi diisi pada data prodi.
                </div>
                @endif
            </div>
            @if($wilayah->count() > 0)
            <div class="card-footer d-flex gap-3 small text-muted">
                <span><span class="badge bg-primary">n</span> = jumlah dosen aktif</span>
                <span><span class="badge bg-info">n</span> = jumlah prodi aktif</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
