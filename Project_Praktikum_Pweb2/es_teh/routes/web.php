<?php

// Controllers Publik
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;

// Controllers untuk User yang Login (Umum)
use App\Http\Controllers\ProfileController; // Dari Breeze
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderSuccessController; // Untuk halaman sukses pesanan
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController as UserOrderController;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\ActivityLogController;

// Controllers Kurir
use App\Http\Controllers\Kurir\DashboardController as KurirDashboardController;
use App\Http\Controllers\Kurir\OrderController as KurirOrderController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Untuk digunakan di route redirect dashboard

/*
|--------------------------------------------------------------------------
| Routes Publik (Bisa Diakses Tamu dan User Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index.public_list');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show.public_detail');

Route::get('/kontak', [ContactController::class, 'create'])->name('contact.page');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Routes yang Memerlukan Autentikasi (Untuk semua peran yang login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirect default untuk /dashboard (misalnya setelah registrasi/login dari Breeze)
    // Route ini akan menangkap jika Breeze atau sistem lain mencoba mengarahkan ke '/dashboard'
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKurir()) {
            return redirect()->route('kurir.dashboard');
        } elseif ($user->isUser()) { // Pastikan method isUser() ada di model User
            return redirect()->route('user.dashboard');
        }
        // Fallback jika tidak ada peran yang cocok atau user biasa tanpa dashboard spesifik
        return redirect()->route('home');
    })->name('dashboard'); // Memberi nama 'dashboard' pada redirector ini

    // User Dashboard & Order Routes
    Route::get('/dashboard-saya', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/pesanan-saya', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/pesanan-saya/{order}', [UserOrderController::class, 'show'])->name('user.orders.show');
    Route::post('/pesanan-saya/{order}/konfirmasi-terima', [UserOrderController::class, 'confirmReception'])->name('user.orders.confirmReception');
    Route::get('/pesanan-sukses/{order}', [OrderSuccessController::class, 'show'])->name('order.success'); // Halaman sukses pesanan

    // Routes untuk Keranjang Belanja
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/keranjang/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Routes untuk Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
});

/*
|--------------------------------------------------------------------------
| Routes Khusus untuk Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('users', AdminUserController::class)->except(['create', 'store', 'show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::patch('/orders/{order}/confirm-qr-payment', [AdminOrderController::class, 'confirmQrPayment'])->name('orders.confirmQrPayment');
    Route::resource('contacts', AdminContactMessageController::class)
        ->parameters(['contacts' => 'contactMessage'])
        ->only(['index', 'show', 'update', 'destroy']);

    // --- ROUTE UNTUK SEMUA AKTIVITAS ---
    Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index');
});

/*
|--------------------------------------------------------------------------
| Routes Khusus untuk Kurir
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', [KurirDashboardController::class, 'index'])->name('dashboard'); // kurir.dashboard
    Route::get('/orders', [KurirOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [KurirOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/update-status', [KurirOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Auth routes dari Laravel Breeze (penting untuk login, register, reset password, dll.)
require __DIR__ . '/auth.php';
