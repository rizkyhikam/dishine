<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Main Controllers
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

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', fn() => view('home'));
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Auth
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register.form');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Produk (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// FAQ
Route::get('/faq', [FAQController::class, 'index'])->name('faq');

// Katalog
Route::get('/katalog', [ProductController::class, 'showKatalog'])->name('katalog');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ========================= CART =========================
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

    // ========================= CHECKOUT =========================
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/full', [CheckoutController::class, 'storeFullCheckout'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'viewOrders'])->name('orders.view');

    // Payment (user)
    Route::post('/payments/upload/{id}', [PaymentController::class, 'uploadProof']);

    // User Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $query = \App\Models\Product::query();

        if ($user->role === 'reseller') {
            $query->where('stok', '>=', 5);
        }

        $products = $query->get();
        return view('user.dashboard', compact('user', 'products'));
    })->name('user.dashboard');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Produk
        Route::get('/products', [AdminController::class, 'manageProducts'])->name('admin.products');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
        Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
        Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.delete');

        // Orders
        Route::get('/orders', [AdminController::class, 'manageOrders'])->name('admin.orders');
        Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update');

        // FAQ
        Route::get('/faqs', [AdminController::class, 'manageFAQ'])->name('admin.faq');
        Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('admin.faq.store');
        Route::put('/faqs/{id}', [AdminController::class, 'updateFaq'])->name('admin.faq.update');
        Route::delete('/faqs/{id}', [AdminController::class, 'destroyFaq'])->name('admin.faq.delete');
    });

/*
|--------------------------------------------------------------------------
| RAJAONGKIR AJAX ROUTES (WEB)
|--------------------------------------------------------------------------
*/
Route::prefix('api/ongkir')->group(function () {
    Route::get('/provinces', [OngkirController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [OngkirController::class, 'getCities']);
    Route::get('/districts/{cityId}', [OngkirController::class, 'getDistricts']);
    Route::get('/sub-districts/{districtId}', [OngkirController::class, 'getSubDistricts']);
    Route::post('/cost', [OngkirController::class, 'getCost']);
});


/*
|--------------------------------------------------------------------------
| DEV: SWITCH ROLE
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
