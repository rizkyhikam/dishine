<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi
     * (Saya sudah tambahkan 'category_id' di sini)
     */
    protected $fillable = [
        'nama',
        'harga_normal',
        'harga_reseller',
        'stok',
        'deskripsi',
        'gambar', // <-- Ini foto sampul (lama)
        'category_id', // <-- Ini link ke kategori (BARU)
    ];

    /**
     * Relasi BARU: Satu Produk ini dimiliki oleh SATU Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi BARU: Satu Produk ini punya BANYAK Gambar Galeri
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

}