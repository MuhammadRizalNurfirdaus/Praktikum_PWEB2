<?php

namespace App\Http\Middleware; // Pastikan namespace ini sesuai dengan lokasi file

use Illuminate\Auth\Middleware\Authenticate as Middleware; // Menggunakan alias untuk class dasar Authenticate dari Laravel
use Illuminate\Http\Request;

class Authenticate extends Middleware // Class Anda meng-extend class dasar Authenticate dari Laravel
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request mengharapkan respons JSON (misalnya, dari panggilan API),
        // maka jangan redirect ke halaman login, kembalikan null agar bisa ditangani sebagai error 401.
        // Jika bukan request JSON, arahkan pengguna ke route yang bernama 'login'.
        return $request->expectsJson() ? null : route('login');
    }
}
