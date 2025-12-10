<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $primaryKey = 'brand_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'country',
        'logo_url',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class, 'brand_id', 'brand_id');
    }
}
