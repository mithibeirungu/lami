<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $primaryKey = 'car_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'brand_id',
        'model',
        'year',
        'body_type_id',
        'transmission',
        'fuel_type',
        'engine_type',
        'engine_size',
        'horsepower',
        'torque',
        'seating_capacity',
        'drive_type',
        'description',
        'thumbnail_image',
        'price',
        'created_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'horsepower' => 'integer',
        'torque' => 'integer',
        'seating_capacity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function bodyType()
    {
        return $this->belongsTo(BodyType::class, 'body_type_id', 'body_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class, 'car_id', 'car_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(CarImage::class, 'car_id', 'car_id')->where('is_primary', true);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'car_id', 'car_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'car_id', 'car_id');
    }
}
