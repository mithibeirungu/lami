<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('car_id');
            $table->string('title', 150);
            $table->unsignedBigInteger('brand_id');
            $table->string('model', 100);
            $table->integer('year');
            $table->unsignedBigInteger('body_type_id');
            $table->string('transmission', 50)->nullable();
            $table->string('fuel_type', 50)->nullable();
            $table->string('engine_type', 50)->nullable();
            $table->string('engine_size', 20)->nullable();
            $table->integer('horsepower')->nullable();
            $table->integer('torque')->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->string('drive_type', 50)->nullable();
            $table->text('description')->nullable();
            $table->text('thumbnail_image')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign keys
            $table->foreign('brand_id')->references('brand_id')->on('brands')->onDelete('cascade');
            $table->foreign('body_type_id')->references('body_type_id')->on('body_types')->onDelete('cascade');
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
