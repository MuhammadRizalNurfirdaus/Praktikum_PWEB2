<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KurirMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isKurir()) { // Menggunakan method isKurir() dari model User
            return $next($request);
        }
        return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai kurir.');
    }
}
