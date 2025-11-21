<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RajaOngkirService;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function getProvinces()
    {
        try {
            $data = $this->rajaOngkir->getProvinces();
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCities($provinceId)
    {
        try {
            $data = $this->rajaOngkir->getCities($provinceId);
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'weight'      => 'required',
            'courier'     => 'required|in:jne,pos,tiki'
        ]);

        try {
            // 1. Ambil ID Kota Asal dari .env
            $origin = env('RAJAONGKIR_ORIGIN', 153); 

            // 2. Ambil Data dari Request User
            $destination = $request->destination;
            $weight = $request->weight;
            $courier = $request->courier;

            // 3. Panggil Service
            $cost = $this->rajaOngkir->getCost(
                $origin, 
                $destination, 
                $weight, 
                $courier
            );
            
            // 4. Return Format yang Sesuai dengan JavaScript
            return response()->json([
                'success' => true,
                'data' => [
                    [
                        'code' => strtoupper($courier),
                        'costs' => $cost
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}