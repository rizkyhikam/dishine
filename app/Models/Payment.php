<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // --- INI PERBAIKAN KEDUA ---
    public const STATUS_BELUM_DIVERIFIKASI = 'belum_diverifikasi';
    public const STATUS_BERHASIL = 'berhasil';
    public const STATUS_GAGAL = 'gagal';
    // -------------------------

    /**
     * Kolom yang boleh diisi (Mass Assignment)
     */
    protected $fillable = [
        'order_id',
        'bukti_transfer',
        'status_verifikasi',
        'metode_pembayaran',
    ];

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}