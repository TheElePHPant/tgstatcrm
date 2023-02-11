<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->bigInteger('topic_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            //
        });
    }
};
