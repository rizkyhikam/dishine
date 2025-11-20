<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Http\Controllers\Admin\SliderController;

// Import Controller
use App\Http\Controllers\{
    AuthController,
    ProductController,
    CartController,
    OrderController,
    PaymentController,
    AdminController,
    FAQController,
    ProfileController,
    HomeController,
    CheckoutController,
    ProductVariantController
};
// Import Controller API Ongkir
use App\Http\Controllers\Api\OngkirController;

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'showKatalog'])->name('katalog');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

// Autentikasi
Route::view('/login', 'auth.login')->name('login')->middleware('guest');
Route::view('/register', 'auth.register')->name('register.form')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// LUPA PASSWORD
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| RUTE USER (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- DASHBOARD & PROFIL ---
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $query = \App\Models\Product::query();
        if ($user->role === 'reseller') {
            $query->where('stok', '>=', 5);
        }
        $products = $query->get();
        return view('user.dashboard', compact('user', 'products'));
    })->name('user.dashboard');
    
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');

    // --- KERANJANG ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/buy-now/{id}', [CartController::class, 'buyNow'])->name('cart.buyNow');

    // --- CHECKOUT ---
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/full', [CheckoutController::class, 'storeFullCheckout'])->name('checkout.store');

    // --- PESANAN ---
    Route::get('/orders', [OrderController::class, 'viewOrders'])->name('orders.view');
    Route::get('/orders/{id}', [OrderController::class, 'showOrder'])->name('orders.show');

    // --- PEMBAYARAN ---
    Route::post('/payments/upload/{id}', [PaymentController::class, 'uploadProof']);

    // ==================================================================
    // LOGIC ALAMAT & ONGKIR (DIPINDAHKAN KE SINI AGAR AMAN)
    // ==================================================================

    // 1. Route Update Alamat (Dipanggil via JS Fetch di Checkout)
    Route::post('/update-alamat', function (Request $request) {
        $request->validate([
            'address_string' => 'required|string',
            'province_id'    => 'required',
            'city_id'        => 'required',
            'postal_code'    => 'required',
        ]);
        
        $user = Auth::user();
        
        // Simpan data ke User
        // Pastikan kolom-kolom ini sudah ada di database users!
        $user->alamat      = $request->address_string;
        $user->province_id = $request->province_id;
        $user->city_id     = $request->city_id;
        $user->district_id = 0; // Set 0 karena input manual
        $user->postal_code = $request->postal_code;
        $user->save();
        
        return response()->json(['message' => 'Alamat berhasil diperbarui']);
    })->name('alamat.update');

    // 2. Route Proxy RajaOngkir (Agar aman diakses user login)
    Route::prefix('api/ongkir')->group(function () {
        Route::get('/provinces', [OngkirController::class, 'getProvinces']);
        Route::get('/cities/{provinceId}', [OngkirController::class, 'getCities']);
        Route::post('/cost', [OngkirController::class, 'getCost']);
    });
});


/*
|--------------------------------------------------------------------------
| RUTE ADMIN (Harus Login & Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Produk
    Route::get('/products', [AdminController::class, 'manageProducts'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.delete');

    // PRODUCT VARIANT
    Route::post('/products/{id}/variants', [ProductVariantController::class, 'store'])->name('variants.store');
    Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.delete');

    // Kategori
    Route::get('/categories', [AdminController::class, 'manageCategories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.delete');

    // Pesanan
    Route::get('/orders', [AdminController::class, 'manageOrders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update');

    // FAQ
    Route::get('/faqs', [AdminController::class, 'manageFAQ'])->name('faq');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('faq.store');
    Route::put('/faqs/{id}', [AdminController::class, 'updateFaq'])->name('faq.update');
    Route::delete('/faqs/{id}', [AdminController::class, 'destroyFaq'])->name('faq.delete');

    // Users
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');

    // Notifikasi
    Route::get('/notifications/read/{id}', [AdminController::class, 'markNotificationAsRead'])->name('notifications.read');

    // --- MANAJEMEN SLIDER ---
    Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
    Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
    Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
    Route::get('/sliders/{id}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
    Route::put('/sliders/{id}', [SliderController::class, 'update'])->name('sliders.update');
    Route::delete('/sliders/{id}', [SliderController::class, 'destroy'])->name('sliders.destroy');

});

/*
|--------------------------------------------------------------------------
| DEV MODE
|--------------------------------------------------------------------------
*/
if (env('DEV_MODE', false)) {
    Route::get('/switch-role/{role}', function ($role) {
        if (!in_array($role, ['admin', 'reseller', 'pelanggan'])) {
            abort(400, 'Role tidak valid.');
        }
        $user = User::where('role', $role)->first() ?? User::create([
            'name' => ucfirst($role) . ' Demo',
            'email' => $role . '@demo.com',
            'password' => bcrypt('password'),
            'role' => $role,
        ]);
        Auth::login($user);
        return redirect()->route(
            $role === 'admin' ? 'admin.dashboard' : 'user.dashboard'
        )->with('success', "Sekarang kamu login sebagai {$role}");
    }); 
}
