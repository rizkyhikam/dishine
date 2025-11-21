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

    private function getHeaders()
    {
        return [
            'key' => $this->apiKey,
            'content-type' => 'application/x-www-form-urlencoded',
            'accept' => 'application/json'
        ];
    }

    public function getProvinces()
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/province");

        if ($response->failed()) return [];
        return $response->json('data');
    }

    public function getCities($provinceId)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/destination/city/{$provinceId}");

        if ($response->failed()) return [];
        return $response->json('data');
    }

    /**
     * HITUNG ONGKIR (DISESUAIKAN DENGAN STRUKTUR JSON ANDA)
     */
    public function getCost($origin, $destination, $weight, $courier)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders($this->getHeaders())
            ->asForm()
            ->post("{$this->baseUrl}/calculate/domestic-cost", [
                'origin'      => $origin,
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier
            ]);

        if ($response->failed()) {
            // Jika error, return array kosong agar tidak crash
            return [];
        }

        $data = $response->json('data');

        // JSON Anda berbentuk Flat Array: [ {code: "jne", cost: 14000}, ... ]
        // Kita perlu memfilter agar hanya mengambil kurir yang diminta user
        // dan memformatnya agar sesuai dengan Controller.

        $results = [];
        if (is_array($data)) {
            foreach ($data as $item) {
                // Filter: Pastikan kode kurirnya sama (misal user pilih 'jne', data 'jne')
                if (isset($item['code']) && strtolower($item['code']) === strtolower($courier)) {
                    
                    $results[] = [
                        'service'     => strtoupper($item['code']) . ' - ' . ($item['service'] ?? 'REG'),
                        'description' => $item['description'] ?? '-',
                        
                        // PERBAIKAN: Ambil langsung field 'cost' (Integer)
                        'cost'        => $item['cost'], 
                        'etd'         => $item['etd'] ?? '-'
                    ];
                }
            }
        }

        return $results;
    }
}