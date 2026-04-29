<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->enum('status_pendaftaran', ['pending', 'approved', 'rejected'])->default('approved')->after('nama_kaprodi');
        });
    }

    public function down()
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn('status_pendaftaran');
        });
    }
};
