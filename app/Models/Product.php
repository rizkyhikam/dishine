<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga_normal',
        'harga_reseller',
        'stok',
        'deskripsi',
        'gambar',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
{
    return $this->hasMany(ProductVariant::class);
}

public function defaultSizes()
{
    return $this->hasMany(DefaultProductSize::class, 'product_id');
}


    // TOTAL STOK OTOMATIS
    public function getTotalStokAttribute()
    {
        if ($this->variants()->count() > 0) {
            return $this->variants->sum('stok');
        }

        return $this->defaultSizes->sum('stok');
    }
}
