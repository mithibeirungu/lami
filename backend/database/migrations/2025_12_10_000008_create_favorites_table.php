<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->bigIncrements('favorite_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id');
            $table->timestamp('created_at')->useCurrent();

            // Foreign keys
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('car_id')->references('car_id')->on('cars')->onDelete('cascade');

            // Unique constraint to prevent duplicate favorites
            $table->unique(['user_id', 'car_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
