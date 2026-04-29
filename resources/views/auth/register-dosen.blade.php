<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - SIMA PAUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #1a3c5e 0%, #2d6a9f 100%); min-height: 100vh; padding: 30px 0; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .card-header-custom { background: #1a3c5e; color: white; border-radius: 16px 16px 0 0; padding: 25px 30px; }
        .nav-tabs .nav-link { color: #555; font-weight: 500; }
        .nav-tabs .nav-link.active { color: #1a3c5e; font-weight: 700; border-bottom: 3px solid #1a3c5e; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center">
                        @if(!empty($globalSetting->logo))
                        <img src="{{ asset('storage/'.$globalSetting->logo) }}" style="height:50px;object-fit:contain;filter:brightness(0) invert(1);margin-right:12px;">
                        @else
                        <i class="bi bi-mortarboard-fill fs-2 me-3"></i>
                        @endif
                        <div>
                            <h4 class="mb-0">Pendaftaran Anggota</h4>
                            <small class="opacity-75">{{ $globalSetting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i>
                        Setelah mendaftar, akun Anda akan <strong>menunggu persetujuan admin</strong> sebelum kartu/piagam member diterbitkan.
                    </div>

                    {{-- Tab pilihan jenis pendaftaran --}}
                    <ul class="nav nav-tabs mb-4" id="regTab">
                        <li class="nav-item">
                            <a class="nav-link {{ old('_tab', 'dosen') !== 'prodi' ? 'active' : '' }}"
                               href="#tab-dosen" data-bs-toggle="tab">
                                <i class="bi bi-person-badge"></i> Dosen Individual
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ old('_tab') === 'prodi' ? 'active' : '' }}"
                               href="#tab-prodi" data-bs-toggle="tab">
                                <i class="bi bi-building"></i> Program Studi
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- TAB DOSEN --}}
                        <div class="tab-pane fade {{ old('_tab', 'dosen') !== 'prodi' ? 'show active' : '' }}" id="tab-dosen">
                            <form action="{{ route('register.dosen.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_tab" value="dosen">
                                <h6 class="text-muted mb-3">
                                    <span class="badge bg-primary rounded-circle me-1">1</span> Data Akun
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="email@universitas.ac.id" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                </div>
                                <h6 class="text-muted mb-3">
                                    <span class="badge bg-primary rounded-circle me-1">2</span> Data Dosen
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap (dengan gelar) <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Dr. Nama Lengkap, M.Pd." required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIDN <span class="text-danger">*</span></label>
                                        <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror"
                                            value="{{ old('nidn') }}" placeholder="0012345678" required>
                                        @error('nidn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Telepon / WhatsApp</label>
                                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                    <select name="prodi_id" class="form-select @error('prodi_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Program Studi --</option>
                                        @foreach($prodis as $p)
                                        <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }} - {{ $p->nama_universitas }} ({{ $p->kota }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jabatan Fungsional</label>
                                        <select name="jabatan_fungsional" class="form-select">
                                            <option value="">-- Pilih --</option>
                                            @foreach(['Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'] as $j)
                                            <option value="{{ $j }}" {{ old('jabatan_fungsional') == $j ? 'selected' : '' }}>{{ $j }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pendidikan Terakhir</label>
                                        <select name="pendidikan_terakhir" class="form-select">
                                            <option value="">-- Pilih --</option>
                                            @foreach(['S1','S2','S3'] as $p)
                                            <option value="{{ $p }}" {{ old('pendidikan_terakhir') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Foto (opsional)</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                    <small class="text-muted">Format JPG/PNG, maks 2MB. Akan digunakan di kartu member.</small>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="bi bi-send"></i> Kirim Pendaftaran Dosen
                                </button>
                            </form>
                        </div>

                        {{-- TAB PRODI --}}
                        <div class="tab-pane fade {{ old('_tab') === 'prodi' ? 'show active' : '' }}" id="tab-prodi">
                            <form action="{{ route('register.prodi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_tab" value="prodi">
                                <h6 class="text-muted mb-3">
                                    <span class="badge bg-info rounded-circle me-1">1</span> Data Akun Program Studi
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Resmi Prodi <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="prodi@universitas.ac.id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                </div>
                                <h6 class="text-muted mb-3">
                                    <span class="badge bg-info rounded-circle me-1">2</span> Data Program Studi
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_prodi" class="form-control" value="{{ old('nama_prodi') }}" placeholder="PG-PAUD" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Universitas <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_universitas" class="form-control" value="{{ old('nama_universitas') }}" placeholder="Universitas ..." required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Kaprodi / Penanggung Jawab <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_kaprodi" class="form-control" value="{{ old('nama_kaprodi') }}" placeholder="Dr. Nama Kaprodi, M.Pd." required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kota</label>
                                        <input type="text" name="kota" class="form-control" value="{{ old('kota') }}" placeholder="Jakarta">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi') }}" placeholder="DKI Jakarta">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Telepon Prodi</label>
                                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}" placeholder="021-xxxxxxx">
                                </div>
                                <button type="submit" class="btn btn-info text-white w-100 py-2">
                                    <i class="bi bi-send"></i> Kirim Pendaftaran Program Studi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
