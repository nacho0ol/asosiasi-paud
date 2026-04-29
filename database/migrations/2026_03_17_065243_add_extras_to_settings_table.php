<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('website');
            $table->string('youtube')->nullable()->after('cap');
            $table->string('instagram')->nullable()->after('youtube');
            $table->string('facebook')->nullable()->after('instagram');
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'youtube', 'instagram', 'facebook']);
        });
    }
};
