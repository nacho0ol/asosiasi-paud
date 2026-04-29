@extends('layouts.app')
@section('title', 'Manajemen Tagihan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Manajemen Tagihan</h5>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Belum Bayar</div>
                        <h4 class="mb-0">{{ $totalBelumBayar }}</h4>
                    </div>
                    <i class="bi bi-exclamation-circle fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">Lunas</div>
                        <h4 class="mb-0">{{ $totalLunas }}</h4>
                    </div>
                    <i class="bi bi-check-circle fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status')=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
                    <option value="lunas" {{ request('status')=='lunas'?'selected':'' }}>Lunas</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="dosen" {{ request('jenis')=='dosen'?'selected':'' }}>Dosen</option>
                    <option value="prodi" {{ request('jenis')=='prodi'?'selected':'' }}>Prodi</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No Tagihan</th>
                        <th>Jenis</th>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tagihans as $t)
                <tr>
                    <td><small class="text-muted">{{ $t->no_tagihan }}</small></td>
                    <td>
                        @if($t->jenis === 'dosen')
                            <span class="badge bg-primary">Dosen</span>
                        @else
                            <span class="badge bg-info text-dark">Prodi</span>
                        @endif
                    </td>
                    <td>{{ $t->nama_ref }}</td>
                    <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                    <td>
                        {{ $t->jatuh_tempo->format('d/m/Y') }}
                        @if($t->status === 'belum_bayar' && $t->jatuh_tempo->isPast())
                            <span class="badge bg-danger ms-1">Lewat</span>
                        @endif
                    </td>
                    <td>
                        @if($t->status === 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                        @endif
                    </td>
                    <td>
                        @if($t->status === 'belum_bayar')
                        <form action="{{ route('tagihan.lunas', $t) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Tandai tagihan ini sebagai lunas?')">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                <i class="bi bi-check-lg"></i> Lunas
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('tagihan.destroy', $t) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus tagihan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada tagihan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $tagihans->withQueryString()->links() }}
    </div>
</div>
@endsection
