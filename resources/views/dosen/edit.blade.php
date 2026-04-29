@extends('layouts.app')
@section('title', 'Edit Dosen')
@section('content')
<div class="card" style="max-width:650px">
    <div class="card-header"><i class="bi bi-pencil"></i> Edit Dosen</div>
    <div class="card-body">
        <form action="{{ route('dosen.update', $dosen) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('dosen._form')
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
