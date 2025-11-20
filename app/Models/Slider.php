<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'image',
        'alt',
        'position',
        'is_active'
    ];

    protected $casts = [
        'position' => 'integer',
        'is_active' => 'boolean',
    ];
}
