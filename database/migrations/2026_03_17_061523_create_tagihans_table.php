<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('no_tagihan')->unique();
            $table->enum('jenis', ['dosen', 'prodi']);
            $table->unsignedBigInteger('ref_id'); // dosen_id atau prodi_id
            $table->decimal('jumlah', 10, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->unsignedBigInteger('pembayaran_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihans');
    }
};
