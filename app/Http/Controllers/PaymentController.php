<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('verifyPayment');
    }

    /**
     * 🧾 Upload bukti transfer oleh user
     */
    public function uploadProof(Request $request, $id, WhatsAppService $whatsapp)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($id);

        // Pastikan order milik user login
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak berhak mengupload bukti untuk pesanan ini'], 403);
        }

        // Hapus bukti lama jika ada
        $existingPayment = Payment::where('order_id', $id)->first();
        if ($existingPayment && $existingPayment->bukti_transfer) {
            Storage::disk('public')->delete($existingPayment->bukti_transfer);
        }

        // Simpan file baru
        $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');
        $buktiUrl = asset('storage/' . $buktiPath);

        // Simpan atau update payment
        $payment = Payment::updateOrCreate(
            ['order_id' => $id],
            [
                'bukti_transfer' => $buktiPath,
                'status_verifikasi' => Payment::STATUS_BELUM_DIVERIFIKASI
            ]
        );

        // Update status order
        $order->update(['status' => Order::STATUS_MENUNGGU_VERIFIKASI]);

        // Kirim notifikasi WA ke admin dengan gambar bukti transfer
        try {
            $message = "💸 *Pembayaran Baru Diterima!*\n"
                . "Dari: {$order->user->name}\n"
                . "ID Pesanan: #{$order->id}\n"
                . "Total: Rp" . number_format($order->total + $order->ongkir, 0, ',', '.') . "\n"
                . "Harap segera *verifikasi pembayaran* di dashboard admin.\n\n"
                . "📎 Bukti transfer terlampir di bawah.";

            $whatsapp->sendToAdmin($message, $buktiUrl);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi WA: ' . $e->getMessage());
        }

        return response()->json([
            'message' => '✅ Bukti transfer berhasil diupload. Menunggu verifikasi admin.',
            'payment' => $payment
        ], 200);
    }

    /**
     * ✅ Verifikasi pembayaran oleh admin
     */
    public function verifyPayment($id, WhatsAppService $whatsapp)
    {
        $payment = Payment::where('order_id', $id)->firstOrFail();
        $payment->update(['status_verifikasi' => Payment::STATUS_SUDAH_DIVERIFIKASI]);

        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => Order::STATUS_DIVERIFIKASI]);
        }

        // Kirim notifikasi ke user via WA
        try {
            $message = "✅ *Pembayaran Kamu Telah Diverifikasi!*\n"
                . "Pesanan #{$order->id} sedang diproses.\n"
                . "Terima kasih sudah berbelanja di *Dishine* 🌸";

            $whatsapp->sendToUser($order->user->no_hp, $message);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim WA ke user: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Pembayaran berhasil diverifikasi dan pesanan siap diproses.'
        ], 200);
    }
}
