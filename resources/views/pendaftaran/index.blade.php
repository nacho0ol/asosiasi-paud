@extends('layouts.app')
@section('title', 'Manajemen Pendaftaran')
@section('content')

@php
    $totalPending = $pendingDosen->count() + $pendingProdi->count();
@endphp

{{-- Tab navigasi --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" href="#tab-dosen" data-bs-toggle="tab">
            <i class="bi bi-person-badge"></i> Dosen
            @if($pendingDosen->count() > 0)
                <span class="badge bg-warning text-dark">{{ $pendingDosen->count() }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#tab-prodi" data-bs-toggle="tab">
            <i class="bi bi-building"></i> Program Studi
            @if($pendingProdi->count() > 0)
                <span class="badge bg-warning text-dark">{{ $pendingProdi->count() }}</span>
            @endif
        </a>
    </li>
</ul>

<div class="tab-content">

    {{-- TAB DOSEN --}}
    <div class="tab-pane fade show active" id="tab-dosen">
        {{-- Pending Dosen --}}
        <div class="card mb-4">
            <div class="card-header bg-warning bg-opacity-25">
                <i class="bi bi-hourglass-split text-warning"></i> Menunggu Persetujuan - Dosen
                <span class="badge bg-warning text-dark ms-1">{{ $pendingDosen->count() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>NIDN</th><th>Email</th><th>Prodi</th><th>Universitas</th><th>Daftar</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                    @forelse($pendingDosen as $d)
                    <tr>
                        <td>
                            @if($d->foto)<img src="{{ asset('storage/'.$d->foto) }}" class="rounded-circle me-1" width="28" height="28" style="object-fit:cover">@endif
                            {{ $d->nama }}
                        </td>
                        <td>{{ $d->nidn }}</td>
                        <td>{{ $d->email }}</td>
                        <td>{{ $d->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $d->prodi->nama_universitas ?? '-' }}</td>
                        <td><small class="text-muted">{{ $d->created_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <form action="{{ route('pendaftaran.approve-dosen', $d) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Setujui pendaftaran {{ $d->nama }}?')">
                                    <i class="bi bi-check-circle"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('pendaftaran.reject-dosen', $d) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak pendaftaran {{ $d->nama }}?')">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">
                        <i class="bi bi-check-all text-success d-block fs-3"></i> Tidak ada pendaftaran dosen yang menunggu
                    </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Approved Dosen --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-check-circle text-success"></i> Dosen Terdaftar
                <span class="badge bg-success ms-1">{{ $approvedDosen->total() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>NIDN</th><th>Prodi</th><th>No Member</th><th>Berlaku s/d</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                    @forelse($approvedDosen as $d)
                    <tr>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->nidn }}</td>
                        <td>{{ $d->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $d->memberDosen->no_member ?? '-' }}</td>
                        <td>{{ $d->memberDosen ? $d->memberDosen->tanggal_berakhir->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($d->memberDosen)
                            <span class="badge bg-{{ $d->memberDosen->status === 'aktif' ? 'success' : 'danger' }}">{{ $d->memberDosen->status }}</span>
                            @else <span class="badge bg-secondary">-</span> @endif
                        </td>
                        <td>
                            @if($d->memberDosen)
                            <a href="{{ route('pdf.kartu-dosen', $d->memberDosen) }}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="bi bi-credit-card"></i> Kartu
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada dosen terdaftar</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($approvedDosen->hasPages())<div class="card-footer">{{ $approvedDosen->links() }}</div>@endif
        </div>
    </div>

    {{-- TAB PRODI --}}
    <div class="tab-pane fade" id="tab-prodi">
        {{-- Pending Prodi --}}
        <div class="card mb-4">
            <div class="card-header bg-warning bg-opacity-25">
                <i class="bi bi-hourglass-split text-warning"></i> Menunggu Persetujuan - Program Studi
                <span class="badge bg-warning text-dark ms-1">{{ $pendingProdi->count() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Nama Prodi</th><th>Universitas</th><th>Kaprodi</th><th>Email</th><th>Kota</th><th>Daftar</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                    @forelse($pendingProdi as $p)
                    <tr>
                        <td>{{ $p->nama_prodi }}</td>
                        <td>{{ $p->nama_universitas }}</td>
                        <td>{{ $p->nama_kaprodi }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->kota }}</td>
                        <td><small class="text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <form action="{{ route('pendaftaran.approve-prodi', $p) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Setujui pendaftaran {{ $p->nama_prodi }}?')">
                                    <i class="bi bi-check-circle"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('pendaftaran.reject-prodi', $p) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak pendaftaran {{ $p->nama_prodi }}?')">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">
                        <i class="bi bi-check-all text-success d-block fs-3"></i> Tidak ada pendaftaran prodi yang menunggu
                    </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Approved Prodi --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-check-circle text-success"></i> Program Studi Terdaftar
                <span class="badge bg-success ms-1">{{ $approvedProdi->total() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Nama Prodi</th><th>Universitas</th><th>No Member</th><th>Berlaku s/d</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                    @forelse($approvedProdi as $p)
                    <tr>
                        <td>{{ $p->nama_prodi }}</td>
                        <td>{{ $p->nama_universitas }}</td>
                        <td>{{ $p->memberProdi->no_member ?? '-' }}</td>
                        <td>{{ $p->memberProdi ? $p->memberProdi->tanggal_berakhir->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($p->memberProdi)
                            <span class="badge bg-{{ $p->memberProdi->status === 'aktif' ? 'success' : 'danger' }}">{{ $p->memberProdi->status }}</span>
                            @else <span class="badge bg-secondary">-</span> @endif
                        </td>
                        <td>
                            @if($p->memberProdi)
                            <a href="{{ route('pdf.piagam-prodi', $p->memberProdi) }}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Piagam
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada prodi terdaftar</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($approvedProdi->hasPages())<div class="card-footer">{{ $approvedProdi->links() }}</div>@endif
        </div>
    </div>

</div>
@endsection
