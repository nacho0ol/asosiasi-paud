<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('no_kwitansi')->unique();
            $table->enum('jenis', ['dosen', 'prodi']);
            $table->unsignedBigInteger('ref_id'); // member_dosen_id or member_prodi_id
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal_bayar');
            $table->string('keterangan')->nullable();
            $table->enum('metode', ['tunai', 'transfer'])->default('tunai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};
