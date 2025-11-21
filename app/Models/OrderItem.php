<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi (Mass Assignment)
     * INI ADALAH PERBAIKANNYA
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'deskripsi_varian',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}