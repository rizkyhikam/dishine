<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultProductSize extends Model
{
    protected $fillable = [
        'product_id',
        'size_id',
        'stok',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
