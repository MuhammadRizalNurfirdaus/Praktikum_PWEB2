<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php', // Path ke file route web Anda
        // api: __DIR__.'/../routes/api.php', // Jika Anda menggunakan API routes
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        // PENTING: Untuk Breeze, biasanya tidak ada konfigurasi path 'auth' khusus di sini.
        // Breeze akan mendaftarkan route-nya sendiri.
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ... (konfigurasi middleware Anda yang lain, pastikan tidak ada yang aneh)
        // Biasanya Breeze akan menambahkan middlewarenya sendiri jika diperlukan
        // atau mengandalkan middleware grup 'web'
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
