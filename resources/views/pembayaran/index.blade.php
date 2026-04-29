@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt"></i> Daftar Pembayaran</span>
        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Catat Pembayaran</a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('pembayaran.index') }}" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari no kwitansi, nama dosen/prodi..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="dosen" {{ request('jenis') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="prodi" {{ request('jenis') === 'prodi' ? 'selected' : '' }}>Prodi</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                @if(request('q') || request('jenis'))
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>No Kwitansi</th><th>Jenis</th><th>Nama</th><th>Jumlah</th><th>Tanggal</th><th>Metode</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($pembayarans as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $p->no_kwitansi }}</strong></td>
                <td><span class="badge bg-{{ $p->jenis === 'dosen' ? 'primary' : 'info' }}">{{ ucfirst($p->jenis) }}</span></td>
                <td>
                    @if($p->jenis === 'dosen')
                        {{ optional(optional($p->memberDosen)->dosen)->nama ?? '-' }}
                    @else
                        {{ optional(optional($p->memberProdi)->prodi)->nama_prodi ?? '-' }}
                    @endif
                </td>
                <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                <td>{{ $p->tanggal_bayar->format('d/m/Y') }}</td>
                <td>{{ ucfirst($p->metode) }}</td>
                <td>
                    <a href="{{ route('pdf.kwitansi', $p) }}" class="btn btn-sm btn-primary" target="_blank" title="Cetak Kwitansi"><i class="bi bi-printer"></i></a>
                    <a href="{{ route('pembayaran.edit', $p) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('pembayaran.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pembayaran ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pembayaran</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($pembayarans->hasPages())
    <div class="card-footer">{{ $pembayarans->links() }}</div>
    @endif
</div>
@endsection
