<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('campaign_stats', function (Blueprint $table) {
            $table->id();

            $table->string('status1')->nullable();
            $table->string('status2')->nullable();
            $table->string('card')->nullable();
            $table->string('campaign')->nullable();
            $table->string('result')->nullable();
            $table->string('start')->nullable();
            $table->string('hold')->nullable();
            $table->string('consumption')->nullable();
            $table->decimal('paid', 10, 2)->nullable();
            $table->decimal('cp', 10, 2)->nullable();
            $table->string('audience')->nullable();
            $table->string('link')->nullable();
            $table->decimal('leads', 10, 2)->nullable();
            $table->text('comment')->nullable();



            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign_stats');
    }
};
