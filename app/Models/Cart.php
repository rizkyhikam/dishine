<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity', 'variant_size_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variantSize() 
    {
        // ASUMSI: Kolom FK di tabel carts adalah variant_size_id
        // ASUMSI: Kita hanya menunjuk ke VariantSize untuk memuaskan Eager Loader, 
        // meskipun logic pencarian stok sebenarnya ada di CartController::findStokRow()
        return $this->belongsTo(VariantSize::class, 'variant_size_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockRow() 
    {
        // Ini adalah relasi dummy, logika pencarian sebenarnya ada di Controller.
        // Namun, Anda bisa membuat fungsi helper untuk menampilkan detailnya.
        // Untuk saat ini, tambahkan saja di $fillable.
    }
}
