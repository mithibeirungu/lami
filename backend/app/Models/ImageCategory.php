<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
    ];

    public function images()
    {
        return $this->hasMany(CarImage::class, 'category_id', 'category_id');
    }
}
