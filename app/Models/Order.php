<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // --- KONSTANTA STATUS (Opsional, biar rapi) ---
    // public const STATUS_PENDING = 'pending';
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi'; // Sesuaikan dengan enum DB
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DIKIRIM = 'dikirim';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * Kolom yang boleh diisi (Mass Assignment)
     * PERBAIKAN: Menambahkan kolom yang hilang dan memperbaiki nama kolom
     */
    protected $fillable = [
        'user_id',
        'nama_penerima',      // <-- PENTING: Ditambahkan
        'no_hp',              // <-- PENTING: Ditambahkan
        'alamat_pengiriman',
        
        'tanggal_pesan',
        'total',              // Subtotal Barang
        'ongkir',
        'biaya_layanan',
        'total_harga',        // <-- PERBAIKAN: Di DB namanya 'total_harga', bukan 'total_bayar'
        
        'status',
        'metode_pembayaran',  // <-- PENTING: Ditambahkan
        'bukti_pembayaran',   // <-- PENTING: Ditambahkan
        
        'kurir',
        'layanan_kurir',
        'kota_tujuan',
    ];

    /**
     * Relasi: User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Order Items
     * Saya kembalikan namanya jadi 'items' agar standar dengan Controller/View umumnya
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    // Alias jika view Anda terlanjur pakai 'orderItems'
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi: Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}