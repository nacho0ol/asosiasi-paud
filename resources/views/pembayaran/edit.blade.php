@extends('layouts.app')
@section('title', 'Edit Pembayaran')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><i class="bi bi-pencil"></i> Edit Pembayaran</div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('pembayaran.update', $pembayaran) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">No Kwitansi</label>
                <input type="text" class="form-control" value="{{ $pembayaran->no_kwitansi }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $pembayaran->jumlah) }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Metode</label>
                    <select name="metode" class="form-select">
                        <option value="tunai" {{ $pembayaran->metode === 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ $pembayaran->metode === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $pembayaran->keterangan) }}">
            </div>
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
