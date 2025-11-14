<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OngkirController;

Route::get('/tes-rute-saya', function () {
    return response()->json(['status' => 'OKE, RUTE BERHASIL TERBACA']);
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini otomatis memiliki prefix "/api"
|--------------------------------------------------------------------------
*/

Route::prefix('ongkir')->group(function () {
    
    // --- RUTE BARU UNTUK FITUR SEARCH (KOMERCE API) ---
    // URL: /api/ongkir/search-destination?q=bandung
    Route::get('/search-destination', [OngkirController::class, 'searchDestination']);

    // URL: /api/ongkir/search-price (method POST)
    Route::post('/search-price', [OngkirController::class, 'searchPrice']);


    // --- RUTE LAMA (RAJAONGKIR) ---
    // Ini tidak akan dipakai di halaman checkout, tapi kita biarkan saja
    // jika mungkin dipakai di tempat lain.
    Route::get('/provinces', [OngkirController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [OngkirController::class, 'getCities']);
    Route::get('/districts/{cityId}', [OngkirController::class, 'getDistricts']);
    Route::get('/sub-districts/{districtId}', [OngkirController::class, 'getSubDistricts']);
    Route::post('/cost', [OngkirController::class, 'getCost']);
});