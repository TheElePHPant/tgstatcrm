<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('2fa_disable_token')->nullable();
        });
    }

    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            //
        });
    }
};
