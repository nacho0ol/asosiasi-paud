<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('dosens', function (Blueprint $table) {
            // pending = baru daftar, approved = sudah disetujui admin, rejected = ditolak
            $table->enum('status_pendaftaran', ['pending', 'approved', 'rejected'])->default('approved')->after('foto');
        });

        Schema::table('users', function (Blueprint $table) {
            // role: admin, dosen, prodi
            $table->string('role')->default('dosen')->after('email');
        });
    }

    public function down()
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('status_pendaftaran');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
