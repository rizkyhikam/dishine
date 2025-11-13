<?php

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
    Api\OngkirController
};
use App\Models\User;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ====================
// HALAMAN UTAMA & HOME
// ====================
Route::get('/', fn() => view('home'));
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ====================
// AUTH ROUTES
// ====================
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register.form');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ====================
// PRODUK
// ====================
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// ====================
// KERANJANG (CART)
// ====================
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
});

// ====================
// CHECKOUT & ORDER
// ====================
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/full', [CheckoutController::class, 'storeFullCheckout'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'viewOrders'])->name('orders.view');
});

// ====================
// PEMBAYARAN
// ====================
Route::middleware('auth')->group(function () {
    Route::post('/payments/upload/{id}', [PaymentController::class, 'uploadProof']);
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::put('/payments/verify/{id}', [PaymentController::class, 'verifyPayment']);
});

// ====================
// DASHBOARD USER
// ====================
Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();
    $query = \App\Models\Product::query();

    // Kalau reseller, hanya tampilkan produk stok >= 5
    if ($user->role === 'reseller') {
        $query->where('stok', '>=', 5);
    }

    $products = $query->get();
    return view('user.dashboard', compact('user', 'products'));
})->name('user.dashboard');

// ====================
// ADMIN ROUTES
// ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Produk
    Route::get('/products', [AdminController::class, 'manageProducts'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.delete');

    // Pesanan
    Route::get('/orders', [AdminController::class, 'manageOrders'])->name('admin.orders');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update');

    // FAQ
    Route::get('/faqs', [AdminController::class, 'manageFAQ'])->name('admin.faq');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('admin.faq.store');
    Route::put('/faqs/{id}', [AdminController::class, 'updateFaq'])->name('admin.faq.update');
    Route::delete('/faqs/{id}', [AdminController::class, 'destroyFaq'])->name('admin.faq.delete');
});

// ====================
// FAQ PUBLIC
// ====================
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

// ====================
// PROFIL
// ====================
Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');

// ====================
// KATALOG
// ====================
Route::get('/katalog', [ProductController::class, 'showKatalog'])->name('katalog');

// ====================
// RAJAONGKIR API ROUTES
// ====================
Route::prefix('api/ongkir')->group(function () {
    Route::get('/provinces', [OngkirController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [OngkirController::class, 'getCities']);
    Route::get('/districts/{cityId}', [OngkirController::class, 'getDistricts']);
    Route::get('/sub-districts/{districtId}', [OngkirController::class, 'getSubDistricts']);
    Route::post('/cost', [OngkirController::class, 'getCost']);
});

// ====================
// DEV MODE: GANTI ROLE
// ====================
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
