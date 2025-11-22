<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Models\Product; // Ditambahkan untuk dashboard user

// --- IMPORT CONTROLLERS ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ShippingController;

// Admin Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\ProductVariantController;

// Api Controllers
use App\Http\Controllers\Api\OngkirController;

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'showKatalog'])->name('katalog');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

/*
|--------------------------------------------------------------------------
| RUTE OTENTIKASI (Login, Register, Logout, Verify)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register.form');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // LUPA PASSWORD
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- VERIFIKASI EMAIL (LOGIC DARI PHPMAILER) ---
// Route ini menangani link yang dikirim ke email user
Route::get('/verify-email/{token}', function ($token) {
    $user = User::where('remember_token', $token)->first();

    if ($user) {
        $user->email_verified_at = now();
        $user->remember_token = null; // Hapus token agar tidak bisa dipakai ulang
        $user->save();
        return redirect('/login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    } else {
        return redirect('/login')->withErrors(['msg' => 'Token verifikasi tidak valid atau kadaluarsa.']);
    }
})->name('verification.verify');


/*
|--------------------------------------------------------------------------
| RUTE USER (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- DASHBOARD & PROFIL ---
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $query = Product::query();
        
        // Jika reseller, filter produk stok >= 5 (Contoh logic)
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
    // LOGIC ALAMAT & ONGKIR
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
        
        // Pastikan kolom-kolom ini ada di tabel users
        $user->alamat      = $request->address_string;
        $user->province_id = $request->province_id;
        $user->city_id     = $request->city_id;
        $user->district_id = 0; // Default 0
        $user->postal_code = $request->postal_code;
        $user->save();
        
        return response()->json(['message' => 'Alamat berhasil diperbarui']);
    })->name('alamat.update');

    // 2. Route Proxy RajaOngkir (Via Api Controller)
    Route::prefix('api/ongkir')->group(function () {
        Route::get('/provinces', [OngkirController::class, 'getProvinces']);
        Route::get('/cities/{provinceId}', [OngkirController::class, 'getCities']);
        Route::post('/cost', [OngkirController::class, 'getCost']);
    });
    
    // 3. Route Shipping (Alternatif Controller)
    Route::prefix('shipping')->group(function () {
        Route::get('/provinces', [ShippingController::class, 'getProvinces']);
        Route::get('/city/{provinceId}', [ShippingController::class, 'getCities']);
        Route::post('/cost', [ShippingController::class, 'calculateShipping']);
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

    // Product Variant
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
| DEV MODE (Untuk Quick Login saat Development)
|--------------------------------------------------------------------------
*/
if (env('DEV_MODE', false)) {
    Route::get('/switch-role/{role}', function ($role) {
        if (!in_array($role, ['admin', 'reseller', 'pelanggan'])) {
            abort(400, 'Role tidak valid.');
        }
        // Buat user dummy jika belum ada
        $user = User::where('role', $role)->first() ?? User::create([
            'nama' => ucfirst($role) . ' Demo', // Sesuaikan kolom 'nama' di DB
            'email' => $role . '@demo.com',
            'password' => bcrypt('password'),
            'role' => $role,
            'no_hp' => '08123456789',
        ]);
        
        Auth::login($user);
        
        return redirect()->route(
            $role === 'admin' ? 'admin.dashboard' : 'user.dashboard'
        )->with('success', "Sekarang kamu login sebagai {$role}");
    }); 
}