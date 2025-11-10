<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FAQController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


// Halaman utama
Route::get('/', function () {
    return view('home');
});

// ====================
// AUTH ROUTES
// ====================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Tampilkan form register
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

// Proses login & register
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


// ====================
// PRODUCT ROUTES
// ====================
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// ====================
// CART ROUTES
// ====================
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart']);
});

// ====================
// ORDER ROUTES
// ====================
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout']);
    Route::post('/orders', [OrderController::class, 'storeOrder']);
    Route::get('/orders', [OrderController::class, 'viewOrders']);
});

// ====================
// PAYMENT ROUTES
// ====================
Route::middleware('auth')->group(function () {
    Route::post('/payments/upload/{id}', [PaymentController::class, 'uploadProof']);
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::put('/payments/verify/{id}', [PaymentController::class, 'verifyPayment']);
});

// ====================
// DASHBOARD PENGGUNA (PELANGGAN & RESELLER)
// ====================
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = Auth::user();
    
    // Ambil semua produk
    $query = \App\Models\Product::query();

    // Kalau reseller, hanya tampilkan produk dengan stok >= 5
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
// FAQ PUBLIC ROUTE
// ====================
Route::get('/faq', [FAQController::class, 'index']);

// ===============================
// 🚧 DEV MODE: Switch User Role
// ===============================
if (env('DEV_MODE', false)) {

    Route::get('/switch-role/{role}', function ($role) {
        // Cek role valid
        if (!in_array($role, ['admin', 'reseller', 'pelanggan'])) {
            abort(400, 'Role tidak valid.');
        }

        // Cari user pertama dengan role tersebut
        $user = User::where('role', $role)->first();

        if (!$user) {
            // Kalau belum ada, bikin user dummy
            $user = User::create([
                'name' => ucfirst($role) . ' Demo',
                'email' => $role . '@demo.com',
                'password' => bcrypt('password'),
                'role' => $role,
            ]);
        }

        // Login otomatis
        Auth::login($user);

        return redirect()->route(
            $role === 'admin' ? 'admin.dashboard' : 'dashboard'
        )->with('success', "Sekarang kamu login sebagai {$role}");
    });
}
