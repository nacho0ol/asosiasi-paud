@extends('layouts.app')
@section('title', 'Detail Dosen')
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                @if($dosen->foto)
                    <img src="{{ asset('storage/'.$dosen->foto) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover">
                @else
                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:100px;height:100px">
                        <i class="bi bi-person-fill text-white fs-1"></i>
                    </div>
                @endif
                <h5>{{ $dosen->nama }}</h5>
                <p class="text-muted">{{ $dosen->jabatan_fungsional }}</p>
                <table class="table table-sm text-start">
                    <tr><td class="text-muted">NIDN</td><td>{{ $dosen->nidn }}</td></tr>
                    <tr><td class="text-muted">Prodi</td><td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $dosen->email }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $dosen->telepon }}</td></tr>
                    <tr><td class="text-muted">Pendidikan</td><td>{{ $dosen->pendidikan_terakhir }}</td></tr>
                </table>
                <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        @if($dosen->memberDosen)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-card-checklist"></i> Kartu Member</span>
                <a href="{{ route('pdf.kartu-dosen', $dosen->memberDosen) }}" class="btn btn-sm btn-primary" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Cetak Kartu
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6"><strong>No Member:</strong><br>{{ $dosen->memberDosen->no_member }}</div>
                    <div class="col-6"><strong>Status:</strong><br>
                        <span class="badge bg-{{ $dosen->memberDosen->status === 'aktif' ? 'success' : 'danger' }} fs-6">{{ $dosen->memberDosen->status }}</span>
                    </div>
                    <div class="col-6 mt-2"><strong>Mulai:</strong><br>{{ $dosen->memberDosen->tanggal_mulai->format('d/m/Y') }}</div>
                    <div class="col-6 mt-2"><strong>Berakhir:</strong><br>{{ $dosen->memberDosen->tanggal_berakhir->format('d/m/Y') }}</div>
                </div>
                <form action="{{ route('member-dosen.perpanjang', $dosen->memberDosen) }}" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-success btn-sm"><i class="bi bi-arrow-clockwise"></i> Perpanjang 1 Tahun</button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            Dosen ini belum memiliki member.
            <a href="{{ route('member-dosen.create') }}" class="btn btn-sm btn-primary ms-2">Daftarkan Sekarang</a>
        </div>
        @endif
    </div>
</div>
@endsection
