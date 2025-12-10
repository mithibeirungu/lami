<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('car_images', function (Blueprint $table) {
            $table->bigIncrements('image_id');
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('image_url');
            $table->string('title', 100)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('car_id')->references('car_id')->on('cars')->onDelete('cascade');
            $table->foreign('category_id')->references('category_id')->on('image_categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_images');
    }
};
