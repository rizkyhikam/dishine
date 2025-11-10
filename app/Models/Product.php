<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga_normal',
        'harga_reseller',
        'stok',
        'gambar',
    ];

    // Relasi: Product hasMany OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}