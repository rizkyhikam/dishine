<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        // Gunakan URL Komerce Anda
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    /**
     * Helper Header (Sama seperti teman Anda)
     */
    private function getHeaders()
    {
        return [
            'key' => $this->apiKey,
            'content-type' => 'application/x-www-form-urlencoded',
            'accept' => 'application/json'
        ];
    }

    /**
     * 1. Get Provinces
     * Endpoint: /destination/province
     */
    public function getProvinces()
    {
        $response = Http::withOptions(['verify' => false]) // Bypass SSL
            ->withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/province");

        if ($response->failed()) {
            return []; // Return kosong jika gagal biar gak error screen
        }
        return $response->json('data');
    }

    /**
     * 2. Get Cities
     * Endpoint: /destination/city/{id}
     */
    public function getCities($provinceId)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/city/{$provinceId}");

        if ($response->failed()) {
            return [];
        }
        return $response->json('data');
    }

    /**
     * 3. HITUNG ONGKIR (Mengikuti Endpoint Teman Anda)
     * Endpoint: /calculate/domestic-cost (Bukan /district/...)
     */
    public function cekOngkirStarter($origin, $destination, $weight, $courier)
    {
        // Endpoint ini pakai form-data/urlencoded
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'key' => $this->apiKey
            ])
            ->asForm() // Otomatis set header x-www-form-urlencoded
            ->post("{$this->baseUrl}/calculate/domestic-cost", [
                'origin'      => $origin,      // ID Kota Asal
                'destination' => $destination, // ID Kota Tujuan
                'weight'      => $weight,
                'courier'     => $courier
            ]);

        if ($response->failed()) {
            // Debugging: Lihat errornya apa
            throw new \Exception('Komerce Error: ' . $response->body());
        }

        // Struktur data Komerce biasanya langsung return 'data'
        $data = $response->json('data');

        // Filter agar sesuai kurir yang diminta (karena Komerce kadang balikin semua kurir)
        $filtered = collect($data)->filter(function($item) use ($courier) {
            return strtolower($item['code']) === strtolower($courier);
        })->first(); // Ambil kurir yang cocok

        // Format ulang agar sesuai dengan yang diharapkan Controller/View kita
        // Kita butuh return array of costs: [ {service, description, cost, etd} ]
        if ($filtered && isset($filtered['costs'])) {
            return collect($filtered['costs'])->map(function($cost) use ($filtered) {
                return [
                    'service'     => $filtered['name'] . ' - ' . $cost['service'], // Gabung nama kurir & service
                    'description' => $cost['description'],
                    'cost'        => $cost['cost'],
                    'etd'         => $cost['etd']
                ];
            })->toArray();
        }

        return [];
    }
}