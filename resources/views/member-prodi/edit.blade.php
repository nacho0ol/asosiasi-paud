@extends('layouts.app')
@section('title', 'Edit Member Prodi')
@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header"><i class="bi bi-pencil"></i> Edit Member Prodi</div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('member-prodi.update', $memberProdi) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Prodi</label>
                <input type="text" class="form-control" value="{{ $memberProdi->prodi->nama_prodi }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">No Member</label>
                <input type="text" class="form-control" value="{{ $memberProdi->no_member }}" disabled>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $memberProdi->tanggal_mulai->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Berakhir</label>
                    <input type="date" name="tanggal_berakhir" class="form-control" value="{{ old('tanggal_berakhir', $memberProdi->tanggal_berakhir->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['aktif','tidak_aktif','expired'] as $s)
                    <option value="{{ $s }}" {{ $memberProdi->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('member-prodi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
