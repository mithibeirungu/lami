<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'comment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'car_id',
        'user_id',
        'content',
    ];

    public const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }
}
