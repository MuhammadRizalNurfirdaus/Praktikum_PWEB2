<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__.'/../routes/api.php', // DIKOMENTARI jika file routes/api.php tidak ada atau tidak digunakan
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Di Laravel 11+, Anda biasanya tidak perlu menambahkan middleware web standar
        // seperti StartSession, EncryptCookies, VerifyCsrfToken di sini secara manual.
        // Laravel sudah mengaturnya.

        // Mendaftarkan alias untuk route middleware
        // Ini adalah tempat utama untuk kustomisasi Anda.
        $middleware->alias([
            // Alias yang mungkin sudah ada dari Breeze atau diperlukan Laravel:
            'auth' => \App\Http\Middleware\Authenticate::class, // Pastikan file ini ada: app/Http/Middleware/Authenticate.php
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Pastikan file ini ada (buat jika tidak ada seperti langkah di atas)
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Jika Anda menggunakan verifikasi email Breeze
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class, // Jika Breeze menggunakannya
            'can' => \Illuminate\Auth\Middleware\Authorize::class, // Untuk otorisasi Gate/Policy

            // Tambahkan alias 'admin' Anda:
            'admin' => \App\Http\Middleware\AdminMiddleware::class, // Pastikan file ini ada: app/Http/Middleware/AdminMiddleware.php

            // Jika nanti ada KurirMiddleware:
            // 'kurir' => \App\Http\Middleware\KurirMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi default untuk penanganan exception.
        // Biasanya tidak perlu diubah kecuali ada kebutuhan spesifik.
    })->create();
