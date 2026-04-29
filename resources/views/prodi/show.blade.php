@extends('layouts.app')
@section('title', 'Detail Prodi')
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-building"></i> {{ $prodi->nama_prodi }}</span>
                <a href="{{ route('prodi.edit', $prodi) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><td class="text-muted">Universitas</td><td>{{ $prodi->nama_universitas }}</td></tr>
                    <tr><td class="text-muted">Kota</td><td>{{ $prodi->kota }}, {{ $prodi->provinsi }}</td></tr>
                    <tr><td class="text-muted">Kaprodi</td><td>{{ $prodi->nama_kaprodi }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $prodi->email }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $prodi->telepon }}</td></tr>
                </table>
            </div>
        </div>
        @if($prodi->memberProdi)
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-award"></i> Status Member</div>
            <div class="card-body">
                <p><strong>No Member:</strong> {{ $prodi->memberProdi->no_member }}</p>
                <p><strong>Berlaku:</strong> {{ $prodi->memberProdi->tanggal_mulai->format('d/m/Y') }} s/d {{ $prodi->memberProdi->tanggal_berakhir->format('d/m/Y') }}</p>
                <p><strong>Status:</strong> <span class="badge bg-{{ $prodi->memberProdi->status === 'aktif' ? 'success' : 'danger' }}">{{ $prodi->memberProdi->status }}</span></p>
                <a href="{{ route('pdf.piagam-prodi', $prodi->memberProdi) }}" class="btn btn-sm btn-primary" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Cetak Piagam</a>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-person-badge"></i> Dosen ({{ $prodi->dosens->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>NIDN</th><th>Jabatan</th><th>Member</th></tr></thead>
                    <tbody>
                    @forelse($prodi->dosens as $d)
                    <tr>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->nidn }}</td>
                        <td>{{ $d->jabatan_fungsional }}</td>
                        <td>
                            @if($d->memberDosen)
                                <span class="badge bg-{{ $d->memberDosen->status === 'aktif' ? 'success' : 'danger' }}">{{ $d->memberDosen->status }}</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada dosen</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
