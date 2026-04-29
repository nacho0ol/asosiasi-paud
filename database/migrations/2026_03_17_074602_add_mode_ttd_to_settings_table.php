<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // gambar = upload TTD gambar saja
            // qr     = QR verifikasi digital saja (tanpa gambar TTD)
            // keduanya = gambar TTD + QR verifikasi
            $table->string('mode_ttd')->default('gambar')->after('cap');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('mode_ttd');
        });
    }
};
