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

    /**
     * 🔹 Ambil daftar kota berdasarkan provinsi
     */
    public function getCities($provinceId)
    {
        try {
            $data = $this->rajaOngkir->getCities($provinceId);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔹 Ambil daftar kecamatan berdasarkan kota
     */
    public function getDistricts($cityId)
    {
        try {
            $data = $this->rajaOngkir->getDistricts($cityId);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🔹 Ambil daftar sub-district berdasarkan kecamatan
     */
    public function getSubDistricts($districtId)
    {
        try {
            $data = $this->rajaOngkir->getSubDistricts($districtId);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🚚 Hitung ongkir antar kecamatan (domestik)
     */
    public function getCost(Request $request)
    {
        $request->validate([
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        try {
            $costs = $this->rajaOngkir->cekOngkir(
                $request->origin,
                $request->destination,
                $request->weight,
                $request->courier
            );

            return response()->json($costs, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
