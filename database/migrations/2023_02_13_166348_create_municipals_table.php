<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->text('name');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipals');
    }
};


