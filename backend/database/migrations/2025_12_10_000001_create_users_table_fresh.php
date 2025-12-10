<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('full_name', 100);
            $table->text('password_hash');
            $table->enum('role', ['overseer', 'motor_scribe', 'driver'])->default('driver');
            $table->text('avatar_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
