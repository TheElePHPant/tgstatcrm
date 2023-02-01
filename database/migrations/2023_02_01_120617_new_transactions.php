<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::dropIfExists('transactions');
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('administrator_id');
            $table->bigInteger('client_id')->nullable();
            $table->string('type')->comment('profit/consumption - приход/расход');
            $table->decimal('amount');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
