<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantSize extends Model
{
    protected $fillable = [
        'product_variant_id',
        'size_id',
        'stok',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
