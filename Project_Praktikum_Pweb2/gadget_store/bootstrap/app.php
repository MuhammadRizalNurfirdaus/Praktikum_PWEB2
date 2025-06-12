<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // <-- Pastikan ini di-use

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // <-- CARI BAGIAN INI
        // Tambahkan alias untuk middleware Anda di sini
        $middleware->alias([ // <-- GUNAKAN METHOD ALIAS
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // Anda bisa menambahkan alias middleware lain di sini jika perlu
            // 'nama_alias_lain' => \App\Http\Middleware\NamaMiddlewareLain::class,
        ]);

        // Anda juga bisa mendaftarkan middleware global, grup, dll. di sini jika perlu
        // $middleware->web(append: [
        //     \App\Http\Middleware\ExampleMiddleware::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
