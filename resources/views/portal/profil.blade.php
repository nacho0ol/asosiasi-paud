<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profil - SIMA PAUD</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>body{background:#f4f6f9}.topbar{background:#1a3c5e;color:white;padding:12px 20px}</style>
</head>
<body>
<div class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        @if(!empty($globalSetting->logo))
        <img src="{{ asset('storage/'.$globalSetting->logo) }}" style="height:32px;object-fit:contain;filter:brightness(0) invert(1);">
        @else
        <i class="bi bi-mortarboard-fill me-1"></i>
        @endif
        <strong>{{ $globalSetting->singkatan ?? 'SIMA PAUD' }}</strong> - Edit Profil
    </div>
    <a href="{{ route('portal.index') }}" class="text-white text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="container py-4">
<div class="card" style="max-width:550px;margin:auto">
    <div class="card-header"><i class="bi bi-person-circle"></i> Edit Profil</div>
    <div class="card-body">
        {{ if(session('success')) }}<div class="alert alert-success">{{ session('success') }}</div>{{ endif }}
        {{ if($errors->any()) }}<div class="alert alert-danger"><ul class="mb-0">{{ foreach($errors->all() as $e) }}<li>{{ $e }}</li>{{ endforeach }}</ul></div>{{ endif }}
        <form action="{{ route('portal.profil.update') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <div class="mb-3"><label class="form-label">Nama</label><input type="text" class="form-control bg-light" value="{{ $dosen->nama }}" disabled></div>
            <div class="mb-3"><label class="form-label">NIDN</label><input type="text" class="form-control bg-light" value="{{ $dosen->nidn }}" disabled></div>
            <div class="mb-3"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="{{ old('telepon', $dosen->telepon) }}"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Jabatan Fungsional</label><select name="jabatan_fungsional" class="form-select"><option value="">-- Pilih --</option>{{ foreach(['Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'] as $j) }}<option value="{{ $j }}" {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) == $j ? 'selected' : '' }}>{{ $j }}</option>{{ endforeach }}</select></div>
                <div class="col-md-6 mb-3"><label class="form-label">Pendidikan Terakhir</label><select name="pendidikan_terakhir" class="form-select"><option value="">-- Pilih --</option>{{ foreach(['S1','S2','S3'] as $p) }}<option value="{{ $p }}" {{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) == $p ? 'selected' : '' }}>{{ $p }}</option>{{ endforeach }}</select></div>
            </div>
            <div class="mb-4"><label class="form-label">Foto Profil</label><input type="file" name="foto" class="form-control" accept="image/*">{{ if($dosen->foto) }}<img src="{{ asset('storage/'.$dosen->foto) }}" class="mt-2 rounded" height="60">{{ endif }}</div>
            <button class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>