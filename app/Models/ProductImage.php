<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'product_id',
        'path',
    ];

    /**
     * Relasi: Satu Gambar Galeri ini dimiliki oleh SATU Produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}