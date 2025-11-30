<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'email', 'expires_at', 'used_at'
    ];

    protected $dates = ['expires_at', 'used_at', 'created_at', 'updated_at'];

    public static function generate(string $email = null, ?\DateTime $expiresAt = null)
    {
        $code = Str::upper(Str::random(12));
        return self::create([
            'code' => $code,
            'email' => $email,
            'expires_at' => $expiresAt,
        ]);
    }

    public function isUsable()
    {
        if ($this->used_at) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }
}
