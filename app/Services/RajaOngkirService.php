<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        // Pastikan .env Anda punya 2 variabel ini
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
        $this->apiKey = env('RAJAONGKIR_API_KEY'); // Pastikan ini 'RAJAONGKIR_API_KEY'
    }

    /**
     * Helper untuk header
     */
    private function getHeaders()
    {
        return [
            'key' => $this->apiKey,
            'accept' => 'application/json',
        ];
    }

    /**
     * 🔹 Ambil daftar provinsi
     */
    public function getProvinces()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/province");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data provinsi: ' . $response->body());
        }
        return $response->json('data');
    }

    /**
     * 🔹 Ambil daftar kota berdasarkan ID provinsi
     * PERBAIKAN: ID sekarang ada di URL
     */
    public function getCities($provinceId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/city/{$provinceId}"); // <-- INI DIPERBAIKI

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data kota: ' . $response->body());
        }
        return $response->json('data');
    }

    /**
     * 🔹 Ambil daftar kecamatan (district) berdasarkan ID kota
     * PERBAIKAN: ID sekarang ada di URL
     */
    public function getDistricts($cityId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/district/{$cityId}"); // <-- INI DIPERBAIKI

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data kecamatan: ' . $response->body());
        }
        return $response->json('data');
    }

    /**
     * 🔹 Ambil daftar kelurahan (sub-district) berdasarkan ID kecamatan
     * PERBAIKAN: ID sekarang ada di URL
     */
    public function getSubDistricts($districtId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/sub-district/{$districtId}"); // <-- INI DIPERBAIKI

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data kelurahan: ' . $response->body());
        }
        return $response->json('data');
    }

    /**
     * 🚚 Hitung ongkir antar KECAMATAN (domestik)
     * (Fungsi ini sudah Sesuai dengan dokumentasi ongkir baru Anda)
     */
    public function cekOngkir($originDistrictId, $destinationDistrictId, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post("{$this->baseUrl}/calculate/district/domestic-cost", [
            'origin' => $originDistrictId,
            'destination' => $destinationDistrictId,
            'weight' => $weight, // gram
            'courier' => $courier, // JNE, POS, TIKI
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal menghitung ongkir: ' . $response->body());
        }

        $json = $response->json();
        
        // DOKUMENTASI BARU: API Komerce mengembalikan SEMUA kurir,
        // dia mengabaikan parameter 'courier' di POST.
        // Jadi, kita harus memfilter hasilnya di sini.
        $allServices = data_get($json, 'data', []);
        
        $filteredServices = collect($allServices)->filter(function($service) use ($courier) {
            // Filter berdasarkan 'code' (jne, pos, tiki)
            return strtolower($service['code']) == strtolower($courier);
        })->map(function($service) {
            // Format ulang agar sesuai dengan JS
            return [
                'service' => $service['service'],
                'description' => $service['description'],
                'cost' => $service['cost'],
                'etd' => $service['etd']
            ];
        })->values(); // Reset keys
        
        return [
            'success' => true,
            'data' => $filteredServices, // Kirim array layanan yang sudah difilter
            'raw' => $json,
        ];
    }
}