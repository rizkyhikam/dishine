<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_pesan',
        'total',
        'ongkir',
        'status',
        'alamat_pengiriman',
    ];

    // Enum status pesanan
    const STATUS_BARU = 'baru';
    const STATUS_DIVERIFIKASI = 'diverifikasi';
    const STATUS_DIKIRIM = 'dikirim';
    const STATUS_SELESAI = 'selesai';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_BARU => 'Baru',
            self::STATUS_DIVERIFIKASI => 'Diverifikasi',
            self::STATUS_DIKIRIM => 'Dikirim',
            self::STATUS_SELESAI => 'Selesai',
        ];
    }

    // Relasi: Order belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Order hasMany OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order hasOne Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}