<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // --- Definisi Status (Sudah ada dari perbaikan checkout) ---
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DIKIRIM = 'dikirim';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'user_id',
        'tanggal_pesan',
        'total',
        'ongkir',
        'biaya_layanan',
        'total_bayar',
        'status',
        'alamat_pengiriman',
        'kurir',
        'layanan_kurir',
        'kota_tujuan',
    ];

    /**
     * -----------------------------------------------------------------
     * INI ADALAH PERBAIKAN UNTUK MASALAH "User Dihapus"
     * Relasi: Satu Pesanan dimiliki oleh SATU User
     * -----------------------------------------------------------------
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke OrderItems (Barang yang dipesan)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Payment (Pembayaran)
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}