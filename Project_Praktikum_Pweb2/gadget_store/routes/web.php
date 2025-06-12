<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController; // Ini untuk tampilan publik
use App\Http\Controllers\Admin\ProductController as AdminProductController; // Import Admin Product Controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- RUTE PUBLIK ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');


// --- RUTE ADMIN ---
// Route group untuk admin, dilindungi middleware auth dan middleware 'admin' (yang sudah Anda buat dan daftarkan)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin (Contoh, Anda perlu membuat DashboardController jika mau pakai ini)
    // Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // CRUD untuk Produk Admin
    Route::resource('products', AdminProductController::class);

    // Anda bisa menambahkan resource controller lain di sini untuk kategori, brand, order, dll.
    // Contoh: Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    // Contoh: Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);
});


// Rute otentikasi (jika Anda menggunakan Breeze atau starter kit lain)
// Pastikan ini ada jika Anda menggunakan middleware 'auth'
// Laravel Breeze biasanya membuat file ini secara otomatis.
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
