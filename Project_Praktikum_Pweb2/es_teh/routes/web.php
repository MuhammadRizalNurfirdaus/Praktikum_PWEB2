<?php

// Controllers Publik
use App\Http\Controllers\HomeController; // Untuk halaman Beranda baru
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;

// Controllers untuk User yang Login (Umum)
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController as UserOrderController;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;

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

Route::get('/', [HomeController::class, 'index'])->name('home'); // <--- PERBAIKAN: Mengarah ke HomeController
Route::get('/produk', [ProductController::class, 'index'])->name('products.index.public_list');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show.public_detail');

// Halaman Kontak sekarang menjadi publik
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
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKurir()) {
            return redirect()->route('kurir.dashboard');
        } elseif ($user->isUser()) {
            return redirect()->route('user.dashboard');
        }
        return redirect()->route('home'); // Fallback ke halaman utama publik
    })->name('dashboard');

    // User Dashboard & Order Routes
    Route::get('/dashboard-saya', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/pesanan-saya', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/pesanan-saya/{order}', [UserOrderController::class, 'show'])->name('user.orders.show');
    Route::post('/pesanan-saya/{order}/konfirmasi-terima', [UserOrderController::class, 'confirmReception'])->name('user.orders.confirmReception');

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
    // Route untuk contact messages disederhanakan
    Route::resource('contacts', AdminContactMessageController::class)
        ->parameters(['contacts' => 'contactMessage']) // Menyesuaikan nama parameter jika modelnya ContactMessage
        ->only(['index', 'show', 'update', 'destroy']); // Nama route akan menjadi admin.contacts.index, dll.
});

/*
|--------------------------------------------------------------------------
| Routes Khusus untuk Kurir
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', [KurirDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [KurirOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [KurirOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/update-status', [KurirOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Auth routes dari Laravel Breeze
require __DIR__ . '/auth.php';
