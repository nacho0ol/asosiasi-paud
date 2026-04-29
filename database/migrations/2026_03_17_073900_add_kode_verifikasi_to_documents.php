<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('kode_verifikasi', 64)->nullable()->unique()->after('metode');
        });
        Schema::table('member_dosens', function (Blueprint $table) {
            $table->string('kode_verifikasi', 64)->nullable()->unique()->after('notif_terkirim');
        });
        Schema::table('member_prodis', function (Blueprint $table) {
            $table->string('kode_verifikasi', 64)->nullable()->unique()->after('status');
        });

        // Isi kode verifikasi untuk data yang sudah ada
        \App\Models\Pembayaran::whereNull('kode_verifikasi')->each(function ($r) {
            $r->update(['kode_verifikasi' => strtoupper(Str::random(12))]);
        });
        \App\Models\MemberDosen::whereNull('kode_verifikasi')->each(function ($r) {
            $r->update(['kode_verifikasi' => strtoupper(Str::random(12))]);
        });
        \App\Models\MemberProdi::whereNull('kode_verifikasi')->each(function ($r) {
            $r->update(['kode_verifikasi' => strtoupper(Str::random(12))]);
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', fn($t) => $t->dropColumn('kode_verifikasi'));
        Schema::table('member_dosens', fn($t) => $t->dropColumn('kode_verifikasi'));
        Schema::table('member_prodis', fn($t) => $t->dropColumn('kode_verifikasi'));
    }
};
