<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Services\RajaOngkirService;

use Illuminate\Support\Facades\Http;



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

            // Format agar sesuai JS: { province_id: 1, province: 'Bali' }

            $formattedData = collect($data)->map(function ($item) {

                return [

                    'province_id' => $item['id'],

                    'province' => $item['name']

                ];

            });

            return response()->json($formattedData, 200);

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

            // Format agar sesuai JS: { city_id: 1, city_name: 'Denpasar', type: 'Kota' }

            $formattedData = collect($data)->map(function ($item) {

                return [

                    'city_id' => $item['id'],

                    'city_name' => $item['name']

                ];

            });

            return response()->json($formattedData, 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);

        }

    }



    /**

     * 🔹 Ambil daftar kecamatan berdasarkan kota

     * PERBAIKAN: Sertakan 'zip_code' dari dokumentasi baru

     */

    public function getDistricts($cityId)
    {
        try {
            $data = $this->rajaOngkir->getDistricts($cityId);
            // Format agar sesuai JS: { district_id: 1, district_name: 'Kuta', zip_code: '80361' }
            $formattedData = collect($data)->map(function ($item) {
                return [
                    'district_id' => $item['id'],
                    'district_name' => $item['name'],
                    'zip_code' => $item['zip_code'] // <-- KODE POS ADA DI SINI
                ];
            });
            return response()->json($formattedData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   

    /**

     * 🔹 Ambil daftar kelurahan (sub-district) berdasarkan kecamatan

     * (Fungsi ini tidak lagi dipakai di JS checkout, tapi kita biarkan)

     */

    public function getSubDistricts($districtId)

    {

        try {

            $data = $this->rajaOngkir->getSubDistricts($districtId);

            $formattedData = collect($data)->map(function ($item) {

                return [

                    'subdistrict_id' => $item['id'],

                    'subdistrict_name' => $item['name'],

                    'postal_code' => $item['zip_code']

                ];

            });

            return response()->json($formattedData, 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);

        }

    }



    /**

     * 🚚 Hitung ongkir

     */

    public function getCost(Request $request)

    {

        $request->validate([

            'destination' => 'required|integer', // Ini ID Kecamatan (District)

            'weight' => 'required|integer|min:1',

            'courier' => 'required|string',

        ]);



        try {

            

            $costs = $this->rajaOngkir->cekOngkir(

                originDistrictId: 1989, // <-- GANTI INI (ID Kecamatan Asal)

                destinationDistrictId: $request->destination,

                weight: $request->weight,

                courier: $request->courier

            );

           

            // Service sudah mem-format 'success' dan 'data'

            return response()->json($costs, 200);



        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

    }

}