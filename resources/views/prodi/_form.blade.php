@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="mb-3">
    <label class="form-label">Nama Prodi <span class="text-danger">*</span></label>
    <input type="text" name="nama_prodi" class="form-control @error('nama_prodi') is-invalid @enderror" value="{{ old('nama_prodi', $prodi->nama_prodi ?? '') }}" required>
    @error('nama_prodi')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Nama Universitas <span class="text-danger">*</span></label>
    <input type="text" name="nama_universitas" class="form-control @error('nama_universitas') is-invalid @enderror" value="{{ old('nama_universitas', $prodi->nama_universitas ?? '') }}" required>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kota</label>
        <input type="text" name="kota" class="form-control" value="{{ old('kota', $prodi->kota ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Provinsi</label>
        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $prodi->provinsi ?? '') }}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Nama Kaprodi</label>
    <input type="text" name="nama_kaprodi" class="form-control" value="{{ old('nama_kaprodi', $prodi->nama_kaprodi ?? '') }}">
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $prodi->email ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Telepon</label>
        <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $prodi->telepon ?? '') }}">
    </div>
</div>
