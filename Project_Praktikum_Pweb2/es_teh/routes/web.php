<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;         // Pastikan di-import
use App\Http\Controllers\CheckoutController;    // Pastikan di-import
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman untuk User Umum & Tamu (Publik)
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index.public_list');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show.public_detail');
// Tambahkan route publik lain seperti Tentang Kami, Kontak di sini jika perlu

/*
|--------------------------------------------------------------------------
| Routes yang Memerlukan Autentikasi Umum (untuk semua user yang login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard default (jika user mencoba akses /dashboard, arahkan ke home)
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard'); // Nama 'dashboard' ini mungkin digunakan oleh Breeze setelah login/registrasi

    // Routes untuk Keranjang Belanja (Hanya untuk user yang login)
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/keranjang/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Routes untuk Checkout (Hanya untuk user yang login)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
});

/*
|--------------------------------------------------------------------------
| Routes Khusus untuk Admin (Memerlukan login + peran admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Nama route: admin.dashboard
    Route::resource('products', AdminProductController::class); // Contoh nama route: admin.products.index
    Route::resource('users', AdminUserController::class)->except(['create', 'store', 'show']); // Contoh nama route: admin.users.edit
    // Tambahkan route admin lainnya di sini
});

// Auth routes dari Laravel Breeze
require __DIR__ . '/auth.php';
