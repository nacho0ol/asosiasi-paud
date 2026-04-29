@extends('layouts.app')
@section('title', 'Tambah Member Dosen')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><i class="bi bi-card-checklist"></i> Daftarkan Member Dosen</div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('member-dosen.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Dosen <span class="text-danger">*</span></label>
                <select name="dosen_id" class="form-select" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $d)
                    <option value="{{ $d->id }}" {{ old('dosen_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama }} ({{ $d->nidn }}) - {{ $d->prodi->nama_prodi ?? '' }}
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
            <a href="{{ route('member-dosen.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
