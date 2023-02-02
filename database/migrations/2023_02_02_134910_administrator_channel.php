<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('administrator_channel', function (Blueprint $table) {
            $table->bigInteger('administrator_id');
            $table->bigInteger('channel_id');
        });
    }
};
