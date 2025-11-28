<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('car_name');
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->string('body_type');
            $table->string('fuel_type');
            $table->integer('engine_power');
            $table->string('transmission');
            $table->integer('top_speed');
            $table->decimal('acceleration', 4, 2);
            $table->text('description');
            $table->string('image_url');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
