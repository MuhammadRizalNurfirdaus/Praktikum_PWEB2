<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah pengguna sudah login DAN memiliki role 'admin'
        // Asumsi di model User Anda ada kolom 'role' dan mungkin method isAdmin()
        if (Auth::check() && Auth::user()->role === 'admin') {
            // atau jika Anda punya method isAdmin() di model User: if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Jika bukan admin, redirect atau beri error
        // abort(403, 'Unauthorized action.'); // Memberi halaman error 403
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.'); // Redirect ke homepage dengan pesan error
    }
}
