@extends('layouts.app')
@section('title', 'Tambah Member Prodi')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><i class="bi bi-award"></i> Daftarkan Member Prodi</div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('member-prodi.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodis as $p)
                    <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_prodi }} - {{ $p->nama_universitas }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                <small class="text-muted">Masa berlaku otomatis 1 tahun</small>
            </div>
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('member-prodi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
