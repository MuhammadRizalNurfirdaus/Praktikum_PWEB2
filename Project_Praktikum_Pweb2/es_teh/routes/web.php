<?php

// Import Facades
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers Publik
use App\Http\Controllers\HomeController;
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
use App\Http\Controllers\Admin\ActivityLogController;

// Controllers Kurir
use App\Http\Controllers\Kurir\DashboardController as KurirDashboardController;
use App\Http\Controllers\Kurir\OrderController as KurirOrderController;

/*
|--------------------------------------------------------------------------
| Routes Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index.public_list');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show.public_detail');
Route::get('/kontak', [ContactController::class, 'create'])->name('contact.page');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Routes yang Memerlukan Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // ... (Route Profile, Dashboard Redirect, User Dashboard, Cart, Checkout) ...
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isKurir()) {
            return redirect()->route('kurir.dashboard');
        }
        if ($user->isUser()) {
            return redirect()->route('user.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/confirm-reception', [UserOrderController::class, 'confirmReception'])->name('orders.confirmReception');
        Route::post('/orders/{order}/feedback', [UserOrderController::class, 'storeFeedback'])->name('orders.feedback.store');
        Route::delete('/orders/{order}/archive', [UserOrderController::class, 'archive'])->name('orders.archive');
    });
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{productId}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    });
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('placeOrder');
    });
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

    // INI ADALAH ROUTE YANG ANDA COBA AKSES
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::patch('/orders/{order}/confirm-qr-payment', [AdminOrderController::class, 'confirmQrPayment'])->name('orders.confirmQrPayment');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::resource('contacts', AdminContactMessageController::class)
        ->parameters(['contacts' => 'contactMessage'])
        ->only(['index', 'show', 'update', 'destroy']);
    Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index');
});

/*
|--------------------------------------------------------------------------
| Routes Khusus untuk Kurir
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', [KurirDashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', KurirOrderController::class)->only(['index', 'show']);
    Route::put('/orders/{order}/update-status', [KurirOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Auth routes dari Laravel Breeze
require __DIR__ . '/auth.php';
