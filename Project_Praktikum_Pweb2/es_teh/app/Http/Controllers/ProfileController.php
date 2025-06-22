<?php

namespace App\Http\Controllers; // Namespace yang benar

use App\Http\Requests\ProfileUpdateRequest; // Request validasi khusus
use Illuminate\Contracts\Auth\MustVerifyEmail; // Untuk pengecekan verifikasi email
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View; // Atau use Inertia\Inertia; use Inertia\Response; jika menggunakan Inertia stack

class ProfileController extends Controller // Meng-extend Controller dasar Laravel
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View // Mengembalikan tipe View
    {
        return view('profile.edit', [ // Mengarah ke view 'resources/views/profile/edit.blade.php'
            'user' => $request->user(),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
