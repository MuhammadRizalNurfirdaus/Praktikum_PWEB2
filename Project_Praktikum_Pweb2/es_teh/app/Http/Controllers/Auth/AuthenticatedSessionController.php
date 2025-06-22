<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
// RouteServiceProvider TIDAK lagi diimpor atau digunakan secara eksplisit untuk HOME di sini
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Panggil method authenticated untuk custom redirect berdasarkan peran.
        $redirectResponse = $this->authenticated($request, Auth::user());

        if ($redirectResponse) {
            return $redirectResponse;
        }

        // Fallback jika authenticated() mengembalikan null (untuk user biasa)
        // Breeze akan mencoba mengarahkan ke URL yang dituju sebelumnya (intended URL),
        // jika tidak ada, defaultnya adalah '/dashboard' (yang sudah kita arahkan ke 'home' di routes/web.php).
        // Atau, kita bisa secara eksplisit mengarahkan ke 'home' di sini.
        return redirect()->intended(route('home')); // Mengarahkan ke route 'home' sebagai fallback
    }

    /**
     * Handle response after user authenticated.
     * Method ini kita tambahkan/override untuk logika redirect berdasarkan peran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function authenticated(Request $request, $user): ?RedirectResponse
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'kurir') {
            return redirect()->route('kurir.dashboard');
        } elseif ($user->role === 'user') {
            // Untuk user biasa, kita arahkan ke 'user.dashboard'
            // RouteServiceProvider::HOME tidak lagi digunakan di sini secara langsung
            return redirect()->route('user.dashboard');
        }

        // Jika peran tidak cocok atau tidak ada redirect spesifik, kembalikan null
        // agar logika `intended()` di method `store()` bisa mengambil alih.
        return null;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Arahkan ke halaman utama publik setelah logout
    }
}
