@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="mb-3">
    <label class="form-label">Program Studi <span class="text-danger">*</span></label>
    <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror" required>
        <option value="">-- Pilih Prodi --</option>
        @foreach($prodis as $p)
        <option value="{{ $p->id }}" {{ old('prodi_id', $dosen->prodi_id ?? '') == $p->id ? 'selected' : '' }}>
            {{ $p->nama_prodi }} - {{ $p->nama_universitas }}
        </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $dosen->nama ?? '') }}" required>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">NIDN <span class="text-danger">*</span></label>
        <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror" value="{{ old('nidn', $dosen->nidn ?? '') }}" required>
        @error('nidn')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $dosen->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jabatan Fungsional</label>
        <select name="jabatan_fungsional" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach(['Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'] as $j)
            <option value="{{ $j }}" {{ old('jabatan_fungsional', $dosen->jabatan_fungsional ?? '') == $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Pendidikan Terakhir</label>
        <select name="pendidikan_terakhir" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach(['S1','S2','S3'] as $p)
            <option value="{{ $p }}" {{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir ?? '') == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Telepon</label>
    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $dosen->telepon ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Foto</label>
    <input type="file" name="foto" class="form-control" accept="image/*">
    @if(!empty($dosen->foto))
    <img src="{{ asset('storage/'.$dosen->foto) }}" class="mt-2 rounded" height="60">
    @endif
</div>
