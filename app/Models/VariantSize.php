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

    /**
     * Relasi ke Size (untuk mendapatkan nama ukuran: S, M, L)
     */
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}