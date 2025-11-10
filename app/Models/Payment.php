<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'bukti_transfer',
        'status_verifikasi',
    ];

    // Enum status pembayaran
    const STATUS_BELUM_DIVERIFIKASI = 'Belum Diverifikasi';
    const STATUS_SUDAH_DIVERIFIKASI = 'Sudah Diverifikasi';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_BELUM_DIVERIFIKASI => 'Belum Diverifikasi',
            self::STATUS_SUDAH_DIVERIFIKASI => 'Sudah Diverifikasi',
        ];
    }

    // Relasi: Payment belongsTo Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}