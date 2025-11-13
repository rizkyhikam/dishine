<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    /**
     * Kirim pesan ke admin
     * Bisa teks saja, atau teks + gambar (jika $imageUrl dikirim)
     */
    public function sendToAdmin(string $message, string $imageUrl = null)
    {
        return $this->sendMessage(env('WA_ADMIN_NUMBER'), $message, $imageUrl);
    }

    /**
     * Kirim pesan ke user
     * Bisa teks saja, atau teks + gambar (jika $imageUrl dikirim)
     */
    public function sendToUser(string $target, string $message, string $imageUrl = null)
    {
        return $this->sendMessage($target, $message, $imageUrl);
    }

    /**
     * Fungsi umum untuk kirim pesan WA (Fonnte / Wablas kompatibel)
     */
    private function sendMessage(string $target, string $message, string $imageUrl = null)
    {
        $payload = [
            'target'  => $target,
            'message' => $message,
        ];

        if ($imageUrl) {
            $payload['url'] = $imageUrl; // kirim juga gambar bukti transfer
        }

        $response = Http::withHeaders([
            'Authorization' => env('WA_API_KEY'),
        ])->post(env('WA_API_URL'), $payload);

        if ($response->failed()) {
            throw new \Exception('Gagal kirim pesan WA: ' . $response->body());
        }

        return $response->json();
    }
}
