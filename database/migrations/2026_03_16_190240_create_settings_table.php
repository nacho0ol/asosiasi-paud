<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asosiasi');
            $table->string('singkatan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('ttd_ketua')->nullable();
            $table->string('nama_ketua')->nullable();
            $table->string('ttd_bendahara')->nullable();
            $table->string('nama_bendahara')->nullable();
            $table->decimal('iuran_dosen', 10, 2)->default(0);
            $table->decimal('iuran_prodi', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
