<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderSuccessController extends Controller
{
    public function show(Order $order)
    {
        // Pastikan order ini milik user yang sedang login, atau tampilkan error jika tidak
        if ($order->user_id !== Auth::id()) {
            // Anda bisa redirect ke halaman lain atau tampilkan pesan error
            // Untuk keamanan, mungkin lebih baik tidak menampilkan detail order orang lain
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Ambil pesan sukses dari session flash
        $successMessage = session('success_message');

        return view('orders.success', compact('order', 'successMessage'));
    }
}
