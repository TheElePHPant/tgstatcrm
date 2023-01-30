<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('channel_id');
            $table->decimal('profit', 10, 2)->default(0.00);
            $table->decimal('consumption', 10, 2)->default(0.00);
            $table->timestamps();
            $table->date('date')->unique();
        });
    }
};
