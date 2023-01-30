<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('channel_id');
            $table->bigInteger('day_subscribers')->default(0);
            $table->bigInteger('total_subscribers')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stats');
    }
};
