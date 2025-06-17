<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;  // PASTIKAN INI ADA
use App\Http\Controllers\CategoryController; // PASTIKAN INI ADA
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () { // <-- AWAL GRUP AUTH
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PASTIKAN DUA BARIS BERIKUT INI ADA, TIDAK DIKOMENTARI, DAN DI DALAM GROUP INI:
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
}); // <-- AKHIR GRUP AUTH

// Jika Anda menggunakan Laravel 11 dengan Breeze terbaru, baris ini SEHARUSNYA TIDAK DIPERLUKAN LAGI.
// Jika Anda masih menggunakannya dan menyebabkan masalah, coba komentari.
require __DIR__ . '/auth.php';
