<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kode TTD unik per penandatangan di settings
        Schema::table('settings', function (Blueprint $table) {
            $table->string('kode_ttd_ketua', 64)->nullable()->after('mode_ttd');
            $table->string('kode_ttd_bendahara', 64)->nullable()->after('kode_ttd_ketua');
            $table->timestamp('ttd_ketua_at')->nullable()->after('kode_ttd_bendahara');
            $table->timestamp('ttd_bendahara_at')->nullable()->after('ttd_ketua_at');
        });

        // Tambah hash dokumen ke pembayaran, member_dosen, member_prodi
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('hash_dokumen', 64)->nullable()->after('kode_verifikasi');
        });
        Schema::table('member_dosens', function (Blueprint $table) {
            $table->string('hash_dokumen', 64)->nullable()->after('kode_verifikasi');
        });
        Schema::table('member_prodis', function (Blueprint $table) {
            $table->string('hash_dokumen', 64)->nullable()->after('kode_verifikasi');
        });

        // Generate kode TTD untuk setting yang sudah ada
        \App\Models\Setting::all()->each(function ($s) {
            $s->update([
                'kode_ttd_ketua'     => strtoupper(Str::random(16)),
                'kode_ttd_bendahara' => strtoupper(Str::random(16)),
                'ttd_ketua_at'       => now(),
                'ttd_bendahara_at'   => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('settings', fn($t) => $t->dropColumn(['kode_ttd_ketua','kode_ttd_bendahara','ttd_ketua_at','ttd_bendahara_at']));
        Schema::table('pembayarans', fn($t) => $t->dropColumn('hash_dokumen'));
        Schema::table('member_dosens', fn($t) => $t->dropColumn('hash_dokumen'));
        Schema::table('member_prodis', fn($t) => $t->dropColumn('hash_dokumen'));
    }
};
