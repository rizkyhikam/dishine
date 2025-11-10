<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('verifyPayment');
    }

    /**
     * User upload bukti transfer
     */
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($id);

        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda tidak berhak mengupload bukti untuk pesanan ini'], 403);
        }

        // Hapus bukti lama jika ada
        $existingPayment = Payment::where('order_id', $id)->first();
        if ($existingPayment && $existingPayment->bukti_transfer) {
            Storage::disk('public')->delete($existingPayment->bukti_transfer);
        }

        $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');

        $payment = Payment::updateOrCreate(
            ['order_id' => $id],
            [
                'bukti_transfer' => $buktiPath,
                'status_verifikasi' => Payment::STATUS_BELUM_DIVERIFIKASI
            ]
        );

        // Ubah status order menjadi "Menunggu Verifikasi"
        $order->update(['status' => Order::STATUS_MENUNGGU_VERIFIKASI]);

        return response()->json([
            'message' => 'Bukti transfer berhasil diupload, menunggu verifikasi admin.',
            'payment' => $payment
        ], 200);
    }

    /**
     * Admin verifikasi pembayaran
     */
    public function verifyPayment($id)
    {
        $payment = Payment::where('order_id', $id)->firstOrFail();
        $payment->update(['status_verifikasi' => Payment::STATUS_SUDAH_DIVERIFIKASI]);

        // Ubah status pesanan menjadi "Diverifikasi"
        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => Order::STATUS_DIVERIFIKASI]);
        }

        return response()->json([
            'message' => 'Pembayaran berhasil diverifikasi dan pesanan siap diproses.'
        ], 200);
    }
}
