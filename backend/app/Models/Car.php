<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'admin_id',
        'user_id',
        'car_name',
        'brand',
        'model',
        'year',
        'body_type',
        'fuel_type',
        'engine_power',
        'transmission',
        'top_speed',
        'acceleration',
        'description',
        'image_url',
        'price',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'post_id');
    }
}
