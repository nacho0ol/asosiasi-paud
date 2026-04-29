@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')
<div class="card" style="max-width:800px">
    <div class="card-header"><i class="bi bi-gear"></i> Pengaturan Sistem</div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Identitas --}}
            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-building"></i> Identitas Asosiasi</h6>
            <div class="mb-3">
                <label class="form-label">Nama Asosiasi <span class="text-danger">*</span></label>
                <input type="text" name="nama_asosiasi" class="form-control" value="{{ old('nama_asosiasi', $setting->nama_asosiasi ?? '') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Singkatan</label>
                    <input type="text" name="singkatan" class="form-control" value="{{ old('singkatan', $setting->singkatan ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Website</label>
                    <input type="text" name="website" class="form-control" value="{{ old('website', $setting->website ?? '') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Tagline / Motto</label>
                <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $setting->tagline ?? '') }}" placeholder="Contoh: Effective, Efficient &amp; Quality of Life">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $setting->alamat ?? '') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $setting->email ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $setting->telepon ?? '') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label"><i class="bi bi-youtube text-danger"></i> YouTube</label>
                    <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $setting->youtube ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram</label>
                    <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $setting->instagram ?? '') }}" placeholder="@username">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook</label>
                    <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $setting->facebook ?? '') }}">
                </div>
            </div>

            {{-- Logo --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Logo Asosiasi <small class="text-muted fw-normal">(JPG/PNG)</small></label>
                <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png" onchange="previewImg(this,'prev-logo')">
                <div class="mt-2">
                    @if(!empty($setting->logo))
                    <img id="prev-logo" src="{{ asset('storage/'.$setting->logo) }}" height="55" class="border rounded p-1">
                    @else
                    <img id="prev-logo" src="#" height="55" class="border rounded p-1 d-none">
                    @endif
                </div>
            </div>

            <hr>

            {{-- Iuran --}}
            <h6 class="fw-bold text-success mb-3"><i class="bi bi-cash-coin"></i> Biaya Iuran Tahunan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Iuran Member Dosen (Rp/tahun)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="iuran_dosen" class="form-control" value="{{ old('iuran_dosen', $setting->iuran_dosen ?? 300000) }}" min="0" step="1000">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Iuran Member Prodi (Rp/tahun)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="iuran_prodi" class="form-control" value="{{ old('iuran_prodi', $setting->iuran_prodi ?? 500000) }}" min="0" step="1000">
                    </div>
                </div>
            </div>

            <hr>

            {{-- Mode TTD --}}
            <h6 class="fw-bold text-danger mb-1"><i class="bi bi-pen"></i> Tanda Tangan Digital</h6>
            <p class="text-muted small mb-3">Pilih mode tanda tangan yang tampil di dokumen PDF (kwitansi, kartu member, piagam).</p>

            <div class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="mode_ttd" id="mode_gambar" value="gambar"
                            {{ old('mode_ttd', $setting->mode_ttd ?? 'gambar') === 'gambar' ? 'checked' : '' }}
                            onchange="toggleModeTtd()">
                        <label class="btn btn-outline-primary w-100 text-start p-3" for="mode_gambar">
                            <i class="bi bi-pen fs-4 d-block mb-1"></i>
                            <strong>TTD Gambar</strong><br>
                            <small>Upload foto/scan tanda tangan</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="mode_ttd" id="mode_qr" value="qr"
                            {{ old('mode_ttd', $setting->mode_ttd ?? 'gambar') === 'qr' ? 'checked' : '' }}
                            onchange="toggleModeTtd()">
                        <label class="btn btn-outline-success w-100 text-start p-3" for="mode_qr">
                            <i class="bi bi-qr-code fs-4 d-block mb-1"></i>
                            <strong>TTD Barcode (QR)</strong><br>
                            <small>QR barcode verifikasi digital</small>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <input type="radio" class="btn-check" name="mode_ttd" id="mode_keduanya" value="keduanya"
                            {{ old('mode_ttd', $setting->mode_ttd ?? 'gambar') === 'keduanya' ? 'checked' : '' }}
                            onchange="toggleModeTtd()">
                        <label class="btn btn-outline-warning w-100 text-start p-3" for="mode_keduanya">
                            <i class="bi bi-patch-check fs-4 d-block mb-1"></i>
                            <strong>Gambar + Barcode</strong><br>
                            <small>TTD gambar &amp; QR verifikasi</small>
                        </label>
                    </div>
                </div>
                <div id="info-mode" class="alert mt-2 py-2 mb-0"></div>
            </div>

            {{-- Upload TTD Gambar --}}
            <div id="section-ttd-gambar">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle"></i>
                    <small>Upload gambar tanda tangan dalam format <strong>JPG/PNG</strong>. PNG transparan didukung penuh.</small>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Nama Ketua Umum</label>
                        <input type="text" name="nama_ketua" class="form-control mb-2"
                               value="{{ old('nama_ketua', $setting->nama_ketua ?? '') }}" placeholder="Nama lengkap ketua">
                        <label class="form-label">TTD Ketua Umum <small class="text-muted">(JPG/PNG transparan)</small></label>
                        <input type="file" name="ttd_ketua" class="form-control" accept=".jpg,.jpeg,.png"
                               onchange="previewImg(this,'prev-ttd-ketua')">
                        <div class="mt-2 text-center border rounded p-2 bg-light" style="min-height:70px">
                            @if(!empty($setting->ttd_ketua))
                            <img id="prev-ttd-ketua" src="{{ asset('storage/'.$setting->ttd_ketua) }}" height="55">
                            @else
                            <img id="prev-ttd-ketua" src="#" height="55" class="d-none">
                            <small class="text-muted d-block mt-2">Belum ada</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Nama Bendahara</label>
                        <input type="text" name="nama_bendahara" class="form-control mb-2"
                               value="{{ old('nama_bendahara', $setting->nama_bendahara ?? '') }}" placeholder="Nama lengkap bendahara">
                        <label class="form-label">TTD Bendahara <small class="text-muted">(JPG/PNG transparan)</small></label>
                        <input type="file" name="ttd_bendahara" class="form-control" accept=".jpg,.jpeg,.png"
                               onchange="previewImg(this,'prev-ttd-bendahara')">
                        <div class="mt-2 text-center border rounded p-2 bg-light" style="min-height:70px">
                            @if(!empty($setting->ttd_bendahara))
                            <img id="prev-ttd-bendahara" src="{{ asset('storage/'.$setting->ttd_bendahara) }}" height="55">
                            @else
                            <img id="prev-ttd-bendahara" src="#" height="55" class="d-none">
                            <small class="text-muted d-block mt-2">Belum ada</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Cap / Stempel</label>
                        <p class="text-muted small">Ditampilkan di atas tanda tangan pada kwitansi.</p>
                        <label class="form-label">Upload Cap <small class="text-muted">(JPG/PNG transparan)</small></label>
                        <input type="file" name="cap" class="form-control" accept=".jpg,.jpeg,.png"
                               onchange="previewImg(this,'prev-cap')">
                        <div class="mt-2 text-center border rounded p-2 bg-light" style="min-height:70px">
                            @if(!empty($setting->cap))
                            <img id="prev-cap" src="{{ asset('storage/'.$setting->cap) }}" height="55">
                            @else
                            <img id="prev-cap" src="#" height="55" class="d-none">
                            <small class="text-muted d-block mt-2">Belum ada</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Barcode QR --}}
            <div id="section-ttd-qr">
                <div class="alert alert-success py-2 mb-3">
                    <i class="bi bi-qr-code"></i>
                    <strong>Cara kerja TTD Barcode:</strong><br>
                    <small>
                        Setiap dokumen PDF akan memiliki <strong>2 QR barcode terpisah</strong>:<br>
                        &bull; <strong>QR Ketua Umum</strong> — menampilkan nama, jabatan, dan tanggal tanda tangan ketua<br>
                        &bull; <strong>QR Bendahara</strong> — menampilkan nama, jabatan, dan tanggal tanda tangan bendahara<br>
                        Kode QR di-generate otomatis. Jika TTD diupload ulang, kode QR lama otomatis tidak valid.
                    </small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Ketua Umum</label>
                        <input type="text" name="nama_ketua" class="form-control"
                               value="{{ old('nama_ketua', $setting->nama_ketua ?? '') }}" placeholder="Nama lengkap ketua">
                        <small class="text-muted">Ditampilkan saat QR di-scan</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Bendahara</label>
                        <input type="text" name="nama_bendahara" class="form-control"
                               value="{{ old('nama_bendahara', $setting->nama_bendahara ?? '') }}" placeholder="Nama lengkap bendahara">
                        <small class="text-muted">Ditampilkan saat QR di-scan</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-qr-code-scan text-success fs-4"></i>
                                    <strong>QR Barcode Ketua Umum</strong>
                                </div>
                                @if(!empty($setting->kode_ttd_ketua))
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>
                                    <code class="small text-muted">{{ $setting->kode_ttd_ketua }}</code>
                                </div>
                                @if($setting->ttd_ketua_at)
                                <small class="text-muted d-block mt-1">Dibuat: {{ $setting->ttd_ketua_at->format('d M Y H:i') }}</small>
                                @endif
                                <a href="{{ url('/verifikasi/ttd-ketua/' . $setting->kode_ttd_ketua . '-TEST') }}"
                                   class="btn btn-sm btn-outline-success mt-2" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i> Test Scan
                                </a>
                                @else
                                <span class="badge bg-secondary">Belum ada — akan dibuat otomatis saat simpan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-qr-code-scan text-primary fs-4"></i>
                                    <strong>QR Barcode Bendahara</strong>
                                </div>
                                @if(!empty($setting->kode_ttd_bendahara))
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary"><i class="bi bi-check-circle"></i> Aktif</span>
                                    <code class="small text-muted">{{ $setting->kode_ttd_bendahara }}</code>
                                </div>
                                @if($setting->ttd_bendahara_at)
                                <small class="text-muted d-block mt-1">Dibuat: {{ $setting->ttd_bendahara_at->format('d M Y H:i') }}</small>
                                @endif
                                <a href="{{ url('/verifikasi/ttd-bendahara/' . $setting->kode_ttd_bendahara . '-TEST') }}"
                                   class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i> Test Scan
                                </a>
                                @else
                                <span class="badge bg-secondary">Belum ada — akan dibuat otomatis saat simpan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pengaturan</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImg(input, id) {
    const img = document.getElementById(id);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleModeTtd() {
    const mode = document.querySelector('input[name="mode_ttd"]:checked')?.value || 'gambar';
    const sGambar = document.getElementById('section-ttd-gambar');
    const sQr = document.getElementById('section-ttd-qr');
    const info = document.getElementById('info-mode');

    if (mode === 'gambar') {
        sGambar.style.display = '';
        sQr.style.display = 'none';
        info.className = 'alert alert-primary mt-2 py-2 mb-0';
        info.innerHTML = '<i class="bi bi-pen"></i> Mode <strong>TTD Gambar</strong>: Upload foto/scan tanda tangan ketua dan bendahara.';
    } else if (mode === 'qr') {
        sGambar.style.display = 'none';
        sQr.style.display = '';
        info.className = 'alert alert-success mt-2 py-2 mb-0';
        info.innerHTML = '<i class="bi bi-qr-code"></i> Mode <strong>TTD Barcode</strong>: QR code verifikasi digital tampil di PDF.';
    } else {
        sGambar.style.display = '';
        sQr.style.display = '';
        info.className = 'alert alert-warning mt-2 py-2 mb-0';
        info.innerHTML = '<i class="bi bi-patch-check"></i> Mode <strong>Gambar + Barcode</strong>: Keduanya tampil di PDF.';
    }
}

document.addEventListener('DOMContentLoaded', toggleModeTtd);
</script>
@endpush
@endsection
