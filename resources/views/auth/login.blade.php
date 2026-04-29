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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
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
</body>
</html>
