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
        // Relasi ke Size (untuk mendapatkan nama ukuran: S, M, L, All Size)
        return $this->belongsTo(Size::class, 'size_id');
    }
}
