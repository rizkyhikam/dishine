<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RajaOngkirService;

class OngkirController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    // --- DATA WILAYAH (Tetap coba pakai API biar dropdown jalan) ---
    
    public function getProvinces()
    {
        try {
            $data = $this->rajaOngkir->getProvinces();
            return response()->json($data);
        } catch (\Exception $e) {
            // Fallback jika API mati total
            return response()->json([
                ['province_id' => 1, 'province' => 'Jawa Barat (Dummy)'],
                ['province_id' => 2, 'province' => 'DKI Jakarta (Dummy)'],
            ]);
        }
    }

    public function getCities($provinceId)
    {
        try {
            $data = $this->rajaOngkir->getCities($provinceId);
            
            // Format data agar frontend tidak bingung
            $formatted = collect($data)->map(function ($item) {
                return [
                    'city_id'   => $item['city_id'],
                    'type'      => $item['type'],
                    'city_name' => $item['city_name'],
                    'postal_code' => $item['postal_code'] ?? ''
                ];
            });
            return response()->json($formatted);
            
        } catch (\Exception $e) {
            // Fallback Dummy
            return response()->json([
                ['city_id' => 1, 'type' => 'Kota', 'city_name' => 'Bandung (Dummy)', 'postal_code' => '40000'],
                ['city_id' => 2, 'type' => 'Kabupaten', 'city_name' => 'Bogor (Dummy)', 'postal_code' => '16000'],
            ]);
        }
    }

    // --- ONGKIR DUMMY (LANGSUNG TEMBAK HASIL) ---

    public function getCost(Request $request)
    {
        // Validasi input tetap jalan biar frontend aman
        $request->validate([
            'destination' => 'required',
            'weight'      => 'required',
            'courier'     => 'required',
        ]);

        // KITA REKAYASA HASILNYA DI SINI
        // Tidak peduli inputnya apa, hasilnya selalu ini:
        $dummyCosts = [
            [
                'service' => 'REGULER (Dummy)',
                'description' => 'Layanan Reguler Cepat',
                'cost' => [
                    ['value' => 20000, 'etd' => '2-3', 'note' => '']
                ]
            ],
            [
                'service' => 'EXPRESS (Dummy)',
                'description' => 'Layanan Kilat Besok Sampai',
                'cost' => [
                    ['value' => 35000, 'etd' => '1-1', 'note' => '']
                ]
            ]
        ];

        // Kembalikan struktur yang sama persis dengan RajaOngkir Asli
        // tapi isinya data palsu di atas
        return response()->json(['success' => true, 'data' => $dummyCosts]);
    }
}