<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'type_of_user',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    public function cars() {
        return $this->hasMany(Car::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function favorites() {
        return $this->hasMany(Favorite::class);
    }
}
