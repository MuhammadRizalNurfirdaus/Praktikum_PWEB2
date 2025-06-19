<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace benar

use App\Http\Controllers\Controller; // Import Controller dasar
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Jika Anda ingin mengizinkan reset password oleh admin
use Illuminate\Validation\Rule; // Untuk validasi peran
use Illuminate\Support\Facades\Auth; // Import Auth facade

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $itemPerPage = 10;

        $users = User::query()
            ->when($searchQuery, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($itemPerPage);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route model binding
    {
        $roles = ['user', 'admin', 'kurir']; // Definisikan peran yang tersedia
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Route model binding
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email harus unik kecuali untuk user ini sendiri
            ],
            'role' => ['required', Rule::in(['user', 'admin', 'kurir'])], // Validasi peran
            'password' => 'nullable|string|min:8|confirmed', // Opsional: jika admin ingin mereset password
        ]);

        // Hanya update password jika diisi
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // Hapus dari array jika kosong agar tidak mengupdate password
        }

        // Jangan biarkan admin mengubah role dirinya sendiri menjadi bukan admin
        // atau mengubah role user lain menjadi admin jika hanya ada satu admin
        if (Auth::id() === $user->id && $validatedData['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah peran Anda sendiri menjadi non-admin.');
        }

        // Tambahan: Logika untuk mencegah tidak ada admin sama sekali
        if ($validatedData['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1 && $user->isAdmin()) {
            return redirect()->back()->with('error', 'Harus ada setidaknya satu admin.');
        }


        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Jangan biarkan admin menghapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Tambahan: Logika untuk mencegah tidak ada admin sama sekali
        if (User::where('role', 'admin')->count() <= 1 && $user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
