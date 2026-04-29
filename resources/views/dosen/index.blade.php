@extends('layouts.app')
@section('title', 'Dosen')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-person-badge"></i> Daftar Dosen</span>
        <a href="{{ route('dosen.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('dosen.index') }}" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama, NIDN, jabatan, prodi..." value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                @if(request('q'))
                    <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Nama</th><th>NIDN</th><th>Prodi</th><th>Jabatan</th><th>Member</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($dosens as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($d->foto)
                        <img src="{{ asset('storage/'.$d->foto) }}" class="rounded-circle me-1" width="28" height="28" style="object-fit:cover">
                    @endif
                    {{ $d->nama }}
                </td>
                <td>{{ $d->nidn }}</td>
                <td>{{ $d->prodi->nama_prodi ?? '-' }}</td>
                <td>{{ $d->jabatan_fungsional }}</td>
                <td>
                    @if($d->memberDosen)
                        <span class="badge bg-{{ $d->memberDosen->status === 'aktif' ? 'success' : 'danger' }}">{{ $d->memberDosen->status }}</span>
                    @else
                        <span class="badge bg-secondary">Belum</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('dosen.show', $d) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('dosen.edit', $d) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('dosen.destroy', $d) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dosen ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data dosen</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($dosens->hasPages())
    <div class="card-footer">{{ $dosens->links() }}</div>
    @endif
</div>
@endsection
