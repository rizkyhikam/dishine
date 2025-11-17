<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OngkirController;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute tes (biarkan saja)
Route::get('/tes-rute-saya', function () {
    return response()->json(['status' => 'OKE, RUTE BERHASIL TERBACA']);
});

// --- RUTE UNTUK ALAMAT DROPDOWN (RAJAONGKIR KOMERCE) ---
Route::prefix('ongkir')->group(function () {
    Route::get('/provinces', [OngkirController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [OngkirController::class, 'getCities']);
    Route::get('/districts/{cityId}', [OngkirController::class, 'getDistricts']);
    Route::get('/sub-districts/{districtId}', [OngkirController::class, 'getSubDistricts']);
    Route::post('/cost', [OngkirController::class, 'getCost']);
});

// --- RUTE BARU UNTUK SIMPAN ALAMAT ---
Route::post('/update-alamat', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    
    $request->validate(['alamat' => 'required|string|max:255']);
    
    $user = Auth::user();
    $user->alamat = $request->alamat;
    $user->save();
    
    return response()->json(['message' => 'Alamat berhasil diperbarui']);
});