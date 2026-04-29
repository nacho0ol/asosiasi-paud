@extends('layouts.app')
@section('title', 'Program Studi')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building"></i> Daftar Program Studi</span>
        <a href="{{ route('prodi.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('prodi.index') }}" class="row g-2">
            <div class="col-md-8">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama prodi, universitas, kota, kaprodi..." value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                @if(request('q'))
                    <a href="{{ route('prodi.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Nama Prodi</th><th>Universitas</th><th>Kota</th><th>Kaprodi</th><th>Dosen</th><th>Member</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($prodis as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->nama_prodi }}</td>
                <td>{{ $p->nama_universitas }}</td>
                <td>{{ $p->kota }}</td>
                <td>{{ $p->nama_kaprodi }}</td>
                <td><span class="badge bg-secondary">{{ $p->dosens_count }}</span></td>
                <td>
                    @if($p->memberProdi)
                        <span class="badge bg-{{ $p->memberProdi->status === 'aktif' ? 'success' : 'danger' }}">{{ $p->memberProdi->status }}</span>
                    @else
                        <span class="badge bg-secondary">Belum</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('prodi.show', $p) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('prodi.edit', $p) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('prodi.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus prodi ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data prodi</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($prodis->hasPages())
    <div class="card-footer">{{ $prodis->links() }}</div>
    @endif
</div>
@endsection
