<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('telegram_username')->nullable();
        });
    }

    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            //
        });
    }
};
