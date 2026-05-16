<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MemberDosenController;
use App\Http\Controllers\MemberProdiController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PortalDosenController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RegisterDosenController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

// Route untuk proses bayar dan nampilin pop-up Midtrans
Route::get('/pembayaran/bayar/{id}', [PembayaranController::class, 'bayar'])->name('pembayaran.bayar');

// Route khusus untuk menerima laporan otomatis dari Midtrans (Webhook)
Route::post('/pembayaran/callback', [PembayaranController::class, 'callback'])->name('pembayaran.callback');

Route::get('/portal/perpanjang', [\App\Http\Controllers\PortalDosenController::class, 'perpanjang'])->name('portal.perpanjang');
Route::get('/portal-prodi/perpanjang', [\App\Http\Controllers\PortalProdiController::class, 'perpanjang'])->name('portal-prodi.perpanjang');
// Verifikasi dokumen (publik, tanpa login)
Route::get('/verifikasi/{tipe}/{kode}', [VerifikasiController::class, 'show'])->name('verifikasi');
// backward compat
Route::get('/verifikasi/{kode}', [VerifikasiController::class, 'showLegacy'])->name('verifikasi.legacy');

// Registrasi dosen mandiri (publik)
Route::get('/daftar-anggota', [RegisterDosenController::class, 'showForm'])->name('register.dosen');
Route::post('/daftar-anggota/dosen', [RegisterDosenController::class, 'store'])->name('register.dosen.store');
Route::post('/daftar-anggota/prodi', [\App\Http\Controllers\RegisterProdiController::class, 'store'])->name('register.prodi.store');

// Portal dosen
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalDosenController::class, 'index'])->name('index');
    Route::get('/profil', [PortalDosenController::class, 'editProfil'])->name('profil');
    Route::put('/profil', [PortalDosenController::class, 'updateProfil'])->name('profil.update');
    Route::get('/kartu', function () {
        $member = auth()->user()->dosen?->memberDosen;
        if (!$member) abort(404, 'Kartu member belum tersedia.');
        return app(\App\Http\Controllers\PdfController::class)->kartuDosen($member);
    })->name('kartu');
    // Portal prodi
    Route::get('/prodi', [\App\Http\Controllers\PortalProdiController::class, 'index'])->name('prodi.index');
    Route::get('/prodi/profil', [\App\Http\Controllers\PortalProdiController::class, 'editProfil'])->name('prodi.profil');
    Route::put('/prodi/profil', [\App\Http\Controllers\PortalProdiController::class, 'updateProfil'])->name('prodi.profil.update');
    Route::get('/prodi/piagam', function () {
        $member = auth()->user()->prodi?->memberProdi;
        if (!$member) abort(404, 'Piagam belum tersedia.');
        return app(\App\Http\Controllers\PdfController::class)->piagamProdi($member);
    })->name('prodi.piagam');
    // Download kwitansi untuk user (dosen & prodi)
    Route::get('/kwitansi/{pembayaran}', function (\App\Models\Pembayaran $pembayaran) {
        $user = auth()->user();
        // Validasi: hanya boleh download kwitansi milik sendiri
        if ($user->role === 'dosen') {
            $dosen = $user->dosen;
            $member = $dosen?->memberDosen;
            abort_unless($member && $pembayaran->jenis === 'dosen' && $pembayaran->ref_id === $member->id, 403);
        } elseif ($user->role === 'prodi') {
            $prodi = $user->prodi;
            $member = $prodi?->memberProdi;
            abort_unless($member && $pembayaran->jenis === 'prodi' && $pembayaran->ref_id === $member->id, 403);
        }
        return app(\App\Http\Controllers\PdfController::class)->kwitansi($pembayaran);
    })->name('kwitansi');
});

// Area admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('prodi', ProdiController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('member-dosen', MemberDosenController::class);
    Route::resource('member-prodi', MemberProdiController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::post('member-dosen/{memberDosen}/perpanjang', [MemberDosenController::class, 'perpanjang'])->name('member-dosen.perpanjang');
    Route::post('member-prodi/{memberProdi}/perpanjang', [MemberProdiController::class, 'perpanjang'])->name('member-prodi.perpanjang');
    Route::get('pdf/kartu-dosen/{memberDosen}', [PdfController::class, 'kartuDosen'])->name('pdf.kartu-dosen');
    Route::get('pdf/piagam-prodi/{memberProdi}', [PdfController::class, 'piagamProdi'])->name('pdf.piagam-prodi');
    Route::get('pdf/kwitansi/{pembayaran}', [PdfController::class, 'kwitansi'])->name('pdf.kwitansi');
    Route::get('pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('pendaftaran/dosen/{dosen}/approve', [PendaftaranController::class, 'approveDosen'])->name('pendaftaran.approve-dosen');
    Route::post('pendaftaran/dosen/{dosen}/reject', [PendaftaranController::class, 'rejectDosen'])->name('pendaftaran.reject-dosen');
    Route::post('pendaftaran/prodi/{prodi}/approve', [PendaftaranController::class, 'approveProdi'])->name('pendaftaran.approve-prodi');
    Route::post('pendaftaran/prodi/{prodi}/reject', [PendaftaranController::class, 'rejectProdi'])->name('pendaftaran.reject-prodi');
    // backward compat
    Route::post('pendaftaran/{dosen}/approve', [PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
    Route::post('pendaftaran/{dosen}/reject', [PendaftaranController::class, 'reject'])->name('pendaftaran.reject');
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('setting', [SettingController::class, 'update'])->name('setting.update');
    Route::get('tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('tagihan/{tagihan}/lunas', [TagihanController::class, 'lunas'])->name('tagihan.lunas');
    Route::delete('tagihan/{tagihan}', [TagihanController::class, 'destroy'])->name('tagihan.destroy');
});
