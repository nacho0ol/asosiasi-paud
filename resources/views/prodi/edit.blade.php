@extends('layouts.app')
@section('title', 'Edit Prodi')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="bi bi-pencil"></i> Edit Program Studi</div>
    <div class="card-body">
        <form action="{{ route('prodi.update', $prodi) }}" method="POST">
            @csrf @method('PUT')
            @include('prodi._form')
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
