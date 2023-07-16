<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'token'];

    static function generateToken(int $prefix): string
    {
        return $prefix . ':' . Str::random(40);
    }
}
