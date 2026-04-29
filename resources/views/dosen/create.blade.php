@extends('layouts.app')
@section('title', 'Tambah Dosen')
@section('content')
<div class="card" style="max-width:650px">
    <div class="card-header"><i class="bi bi-person-plus"></i> Tambah Dosen</div>
    <div class="card-body">
        <form action="{{ route('dosen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('dosen._form')
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
