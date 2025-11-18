<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order; // <-- Tambahkan ini

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(Order $order) // <-- Terima data Pesanan
    {
        $this->order = $order;
    }

    /**
     * Tentukan channel pengiriman notifikasi.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // <-- KITA HANYA SIMPAN DI DATABASE
    }

    /**
     * Ubah notifikasi menjadi format array (untuk disimpan di database).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Data inilah yang akan disimpan di database
            'order_id' => $this->order->id,
            'user_name' => $this->order->user->nama, // Ambil nama pemesan
            'total_bayar' => $this->order->total_bayar,
            'message' => "Pesanan baru (ORD-{$this->order->id}) telah masuk!",
        ];
    }
}