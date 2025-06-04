<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $casts = [
        'is_available' => 'boolean',
        'dietary_info' => 'array',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value / 100,
            set: fn (float $value) => $value * 100,
        );
    }
}
