<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Http; // <-- PENTING: TAMBAHKAN INI

class OngkirController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    // =================================================================
    // FUNGSI BARU BERDASARKAN SCREENSHOT (KOMERCE API)
    // =================================================================

    /**
     * 🔎 Mencari destinasi (untuk autocomplete)
     * Ini dipanggil setiap kali user mengetik di kotak pencarian.
     */
    public function searchDestination(Request $request)
    {
        $keyword = $request->input('q', ''); // Ambil keyword dari ?q=...

        // Jangan cari jika keyword terlalu pendek (hemat API)
        if (strlen($keyword) < 3) {
            return response()->json([]); // Kembalikan array kosong
        }

        try {
            // PENTING: Pastikan Anda sudah setting KOMERCE_API_KEY di file .env Anda
            $response = Http::withHeader(
                'Authorization', 'Bearer ' . env('KOMERCE_API_KEY')
            )->get('https://api.komerce.id/api/v1/destination/domestic-destination', [
                'q' => $keyword
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gagal mengambil data dari Komerce API'], $response->status());
            }

            // Kembalikan hanya array 'data' (sesuai screenshot)
            // Di frontend, kita akan looping data ini
            return response()->json($response->json('data'), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 💰 Mencari harga ongkir (untuk cek ongkir)
     * Ini dipanggil setelah user memilih lokasi dan kurir.
     */
    public function searchPrice(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string', // 'jne', 'pos', 'tiki'
        ]);

        try {
            // PENTING: Pastikan Anda sudah setting KOMERCE_API_KEY di file .env Anda
            // Kita panggil API Komerce untuk harga
            $response = Http::withHeader(
                'Authorization', 'Bearer ' . env('KOMERCE_API_KEY')
            )->get('https://api.komerce.id/api/v1/shipping/domestic-shipping', [
                'origin' => 157, // ID Kota Bogor (Sesuai kode lama Anda)
                'destination' => $request->destination_id,
                'weight' => $request->weight
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Gagal mengambil data harga'], $response->status());
            }

            // API Komerce mengembalikan SEMUA kurir.
            // Kita filter di sini berdasarkan kurir yang dipilih user.
            $allServices = $response->json('data');
            $filteredServices = [];

            foreach ($allServices as $service) {
                // $service['code'] akan berisi 'jne', 'tiki', 'pos', dll.
                if (strtolower($service['code']) == strtolower($request->courier)) {
                    // $service['costs'] berisi layanannya (REG, OKE, YES, dll)
                    $filteredServices = $service['costs'];
                    break;
                }
            }
            
            // Kembalikan hanya layanan yang sudah difilter
            return response()->json($filteredServices, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // =================================================================
    // FUNGSI LAMA (RAJAONGKIR API)
    // =================================================================

    /**
     * 🔹 Ambil semua provinsi
     */
    public function getProvinces()
    {
        try {
            $data = $this->rajaOngkir->getProvinces();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ... (Fungsi getCities, getDistricts, getSubDistricts, getCost Anda yang lama) ...
    // Biarkan saja di sini, tidak perlu dihapus
    public function getCities($provinceId) { /* ... kode lama ... */ }
    public function getDistricts($cityId) { /* ... kode lama ... */ }
    public function getSubDistricts($districtId) { /* ... kode lama ... */ }
    public function getCost(Request $request) { /* ... kode lama ... */ }
}