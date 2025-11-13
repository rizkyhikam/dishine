<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    /**
     * 🔹 Ambil daftar provinsi
     */
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'accept' => 'application/json',
        ])->get("{$this->baseUrl}/destination/province");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data provinsi: ' . $response->body());
        }

        return $response->json('data');
    }

    /**
     * 🔹 Ambil daftar kota berdasarkan ID provinsi
     */
    public function getCities($provinceId)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'accept' => 'application/json',
        ])->get("{$this->baseUrl}/destination/city/{$provinceId}");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data kota: ' . $response->body());
        }

        return $response->json('data');
    }

    /**
     * 🔹 Ambil daftar kecamatan berdasarkan ID kota
     */
    public function getDistricts($cityId)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'accept' => 'application/json',
        ])->get("{$this->baseUrl}/destination/district/{$cityId}");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data kecamatan: ' . $response->body());
        }

        return $response->json('data');
    }

    /**
     * 🔹 Ambil daftar sub-district berdasarkan ID kecamatan (opsional)
     */
    public function getSubDistricts($districtId)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'accept' => 'application/json',
        ])->get("{$this->baseUrl}/destination/sub-district/{$districtId}");

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data sub-district: ' . $response->body());
        }

        return $response->json('data');
    }

    /**
     * 🚚 Hitung ongkir antar kecamatan (domestik)
     */
    public function cekOngkir($originDistrictId, $destinationDistrictId, $weight, $courier)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
            'accept' => 'application/json',
        ])->post("{$this->baseUrl}/calculate/district/domestic-cost", [
            'origin' => $originDistrictId,
            'destination' => $destinationDistrictId,
            'weight' => $weight, // gram
            'courier' => $courier,
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal menghitung ongkir: ' . $response->body());
        }

        $json = $response->json();

        // Ambil cost pertama dari hasil API
        $cost = data_get($json, 'data.results.0.costs.0.cost.0.value', 0);

        return [
            'success' => true,
            'cost' => $cost,
            'raw' => $json,
        ];
    }
}
