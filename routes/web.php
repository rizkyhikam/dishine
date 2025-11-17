<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Import semua Controller dalam satu grup
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
    CheckoutController
};

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [ProductController::class, 'showKatalog'])->name('katalog');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show'); // (Nama rute diganti agar tidak bentrok)
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

// Autentikasi (Login & Register)
Route::view('/login', 'auth.login')->name('login')->middleware('guest');
Route::view('/register', 'auth.register')->name('register.form')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| RUTE USER (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- PROFIL & DASHBOARD USER ---
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

    // --- KERANJANG (CART) ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // --- CHECKOUT ---
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/full', [CheckoutController::class, 'storeFullCheckout'])->name('checkout.store');

    // --- PESANAN (ORDERS) ---
    Route::get('/orders', [OrderController::class, 'viewOrders'])->name('orders.view');
    
    // --- INI PERBAIKAN UNTUK 404 ANDA ---
    // Rute ini dipindahkan dari grup 'admin' ke grup 'user'
    Route::get('/orders/{id}', [OrderController::class, 'showOrder'])->name('orders.show');

    // --- PEMBAYARAN (PAYMENT) ---
    Route::post('/payments/upload/{id}', [PaymentController::class, 'uploadProof']);
});


/*
|--------------------------------------------------------------------------
| RUTE ADMIN (Harus Login & Role Admin)
|--------------------------------------------------------------------------
*/
// Saya mengganti nama prefix 'admin.' menjadi 'admin.' agar konsisten
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // --- MANAJEMEN PRODUK ---
    Route::get('/products', [AdminController::class, 'manageProducts'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.delete');

    // --- MANAJEMEN KATEGORI ---
    Route::get('/categories', [AdminController::class, 'manageCategories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.delete');

    // --- MANAJEMEN PESANAN ---
    Route::get('/orders', [AdminController::class, 'manageOrders'])->name('orders');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update');
    
    // --- TAMBAHKAN RUTE BARU INI UNTUK DETAIL PESANAN ADMIN ---
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('orders.show');
    
    
    // --- INI BARIS 108 YANG SAYA PERBAIKI ---
    // Saya kembalikan ke fungsi Anda yang asli 'updateOrderStatus'
    // dan saya tambahkan '])->name(...);' yang hilang
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update');

    // --- MANAJEMEN FAQ ---
    Route::get('/faqs', [AdminController::class, 'manageFAQ'])->name('faq');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('faq.store');
    Route::put('/faqs/{id}', [AdminController::class, 'updateFaq'])->name('faq.update');
    Route::delete('/faqs/{id}', [AdminController::class, 'destroyFaq'])->name('faq.delete');
});


/*
|--------------------------------------------------------------------------
| RUTE API (SUDAH DIHAPUS)
|--------------------------------------------------------------------------
|
| Bagian 'api/ongkir' Anda sudah saya hapus dari file ini.
| Rute-rute itu SUDAH ADA dan SEHARUSNYA HANYA ADA di file 'routes/api.php'.
|
*/


/*
|--------------------------------------------------------------------------
| DEV: SWITCH ROLE (Biarkan saja)
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