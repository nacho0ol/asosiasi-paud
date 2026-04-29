<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dokumen Tidak Ditemukan</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
body { background: linear-gradient(135deg, #6c1a1a 0%, #c0392b 100%); min-height: 100vh; display: flex; align-items: center; }
.card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
</style>
</head>
<body>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-5">
    <div class="card">
        <div class="card-body p-4 text-center">
            @if(!empty($setting->logo))
            <img src="{{ asset('storage/'.$setting->logo) }}" style="height:45px;object-fit:contain;" class="mb-3">
            @endif
            <div class="mb-3">
                <i class="bi bi-shield-x text-danger" style="font-size:3rem"></i>
            </div>
            <h5 class="text-danger">Dokumen Tidak Ditemukan</h5>
            <p class="text-muted">Kode verifikasi <strong>{{ $kode }}</strong> tidak ditemukan dalam sistem kami.</p>
            <p class="text-muted small">Dokumen ini mungkin tidak valid atau kode QR tidak terbaca dengan benar.</p>
            <hr>
            <div class="text-muted small">
                {{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}<br>
                {{ $setting->website ?? '' }}
            </div>
        </div>
    </div>
</div>
</div>
</div>
</body>
</html>
