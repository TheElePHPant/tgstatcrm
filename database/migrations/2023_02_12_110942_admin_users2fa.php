<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->boolean('enable_2fa')->default(false);
            $table->string('token_2fa')->nullable();
            $table->timestamp('token_2fa_expires')->nullable();
        });
    }

    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            //
        });
    }
};
