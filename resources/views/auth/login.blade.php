<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMA PAUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #1a3c5e 0%, #2d6a9f 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .login-header { background: #1a3c5e; color: white; border-radius: 16px 16px 0 0; padding: 30px; text-align: center; }
        .login-header i { font-size: 3rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card login-card">
                <div class="login-header">
                    @if(!empty($globalSetting->logo))
                    <img src="{{ asset('storage/'.$globalSetting->logo) }}" style="height:60px;object-fit:contain;margin-bottom:8px;filter:brightness(0) invert(1);">
                    @else
                    <i class="bi bi-mortarboard-fill"></i>
                    @endif
                    <h4 class="mt-2 mb-0">{{ $globalSetting->singkatan ?? 'SIMA PAUD' }}</h4>
                    <small class="opacity-75">{{ $globalSetting->nama_asosiasi ?? 'Sistem Informasi Member Asosiasi PAUD' }}</small>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i> Email atau password salah.
                    </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@paud.id" required autofocus>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </button>
                        
                        {{-- Tombol Bantuan untuk memanggil ulang Popup Info (Opsional, buat jaga-jaga kalau ditutup) --}}
                        <button type="button" class="btn btn-outline-info w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#infoBiayaModal">
                            <i class="bi bi-info-circle"></i> Cek Info Biaya & Syarat
                        </button>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    Belum punya akun? <a href="{{ route('register.dosen') }}">Daftar sebagai Anggota</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL / POP-UP INFO BIAYA --}}
<div class="modal fade" id="infoBiayaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info bg-opacity-10 text-info-emphasis border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-megaphone-fill text-info me-2"></i>Pengumuman Penting!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-3">Sebelum Anda mendaftar atau masuk ke dalam portal, mohon perhatikan rincian biaya wajib untuk aktivasi keanggotaan:</p>
                
                <ul class="list-group list-group-flush mb-3 border rounded">
                    <li class="list-group-item bg-light">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <strong><i class="bi bi-person-badge text-primary me-2"></i> Keanggotaan Dosen</strong>
                            <span class="badge bg-primary rounded-pill">Masa Aktif: 4 Tahun</span>
                        </div>
                        <p class="mb-0 mt-2 text-muted small">Biaya Iuran: <strong>Rp {{ number_format($globalSetting->iuran_dosen ?? 300000, 0, ',', '.') }}</strong></p>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <strong><i class="bi bi-building text-success me-2"></i> Keanggotaan Prodi</strong>
                            <span class="badge bg-success rounded-pill">Masa Aktif: 1 Tahun</span>
                        </div>
                        <p class="mb-0 mt-2 text-muted small">Biaya Iuran: <strong>Rp {{ number_format($globalSetting->iuran_prodi ?? 500000, 0, ',', '.') }}</strong></p>
                    </li>
                </ul>

                <div class="alert alert-warning py-2 mb-0" style="font-size: 0.85rem;">
                    <i class="bi bi-lightning-charge-fill text-warning"></i> <strong>Sistem Otomatis:</strong> Pembayaran dilakukan via Midtrans (QRIS/VA). Status akun Anda akan langsung <strong>Aktif</strong> tanpa perlu menunggu verifikasi admin.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-primary px-5 rounded-pill" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('infoBiayaModal'));
        myModal.show();
    });
</script>
</body>
</html>