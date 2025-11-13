<?php

use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OngkirController;


Route::get('/cart/total', [CartApiController::class, 'getTotal']);
Route::get('/ongkir/{kurir}', [OngkirController::class, 'getCost']);



Route::get('/ongkir/provinces', [OngkirController::class, 'getProvinces']);
Route::get('/ongkir/cities/{provinceId}', [OngkirController::class, 'getCities']);
Route::post('/ongkir/cost', [OngkirController::class, 'getCost']);
