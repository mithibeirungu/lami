<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    use HasFactory;

    protected $primaryKey = 'image_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'car_id',
        'category_id',
        'image_url',
        'title',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }

    public function category()
    {
        return $this->belongsTo(ImageCategory::class, 'category_id', 'category_id');
    }
}
