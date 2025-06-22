<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request; // Tambahkan jika Anda menggunakan $request
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // Anda bisa menambahkan Request $request jika ada filter
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Misalnya, 10 pesanan per halaman

        return view('user.orders.index', compact('orders')); // PASTIKAN NAMA VIEW INI BENAR
    }

    // ... method show dan confirmReception tetap sama ...
    public function show(Order $order)
    {
        // Pastikan pesanan ini milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $order->load('items.product'); // Eager load item dan produknya
        return view('user.orders.show', compact('order')); // PASTIKAN NAMA VIEW INI BENAR
    }
    public function confirmReception(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if (in_array($order->status, ['shipped'])) { // Hanya jika status 'shipped'
            $oldStatus = $order->status;
            $order->status = 'delivered';
            $order->payment_status = 'paid'; // Asumsikan jika diterima, pembayaran (misal COD) selesai
            $order->save();

            log_activity(
                "Pelanggan {$order->customer_name} mengonfirmasi penerimaan pesanan #{$order->order_number}.",
                $order,
                ['old_status' => $oldStatus, 'new_status' => 'delivered'],
                'Pesanan'
            );

            return redirect()->route('user.orders.show', $order->id)->with('success', 'Pesanan telah dikonfirmasi diterima.');
        }
        return redirect()->route('user.orders.show', $order->id)->with('error', 'Pesanan tidak dapat dikonfirmasi saat ini atau status tidak sesuai.');
    }
}
