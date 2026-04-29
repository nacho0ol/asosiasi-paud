@extends('layouts.app')
@section('title', 'Member Prodi')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-award"></i> Daftar Member Prodi</span>
        <a href="{{ route('member-prodi.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Tambah</a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('member-prodi.index') }}" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari no member, nama prodi, universitas..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                @if(request('q') || request('status'))
                    <a href="{{ route('member-prodi.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>No Member</th><th>Prodi</th><th>Universitas</th><th>Mulai</th><th>Berakhir</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($members as $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $m->no_member }}</strong></td>
                <td>{{ $m->prodi->nama_prodi }}</td>
                <td>{{ $m->prodi->nama_universitas }}</td>
                <td>{{ $m->tanggal_mulai->format('d/m/Y') }}</td>
                <td>{{ $m->tanggal_berakhir->format('d/m/Y') }}</td>
                <td>
                    <span class="badge bg-{{ $m->status === 'aktif' ? 'success' : ($m->status === 'expired' ? 'danger' : 'secondary') }}">
                        {{ $m->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('pdf.piagam-prodi', $m) }}" class="btn btn-sm btn-primary" target="_blank" title="Cetak Piagam"><i class="bi bi-file-earmark-pdf"></i></a>
                    <a href="{{ route('member-prodi.edit', $m) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('member-prodi.perpanjang', $m) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success" title="Perpanjang"><i class="bi bi-arrow-clockwise"></i></button>
                    </form>
                    <form action="{{ route('member-prodi.destroy', $m) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data member prodi</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
    <div class="card-footer">{{ $members->links() }}</div>
    @endif
</div>
@endsection
