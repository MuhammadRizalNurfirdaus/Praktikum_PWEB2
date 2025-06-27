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
    public function index()
    {
        $user = Auth::user();
        // Hanya tampilkan pesanan yang tidak diarsipkan oleh user
        $orders = Order::where('user_id', $user->id)
            ->where('archived_by_user', false) // <--- KONDISI BARU
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.orders.index', compact('orders'));
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
    /**
     * Simpan feedback dari user untuk sebuah pesanan (produk dan kurir).
     */
    public function storeFeedback(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'delivered') {
            abort(403, 'Akses ditolak atau pesanan belum selesai.');
        }

        $validatedData = $request->validate([
            'product_rating' => 'required|integer|min:1|max:5', // Nama input harus 'product_rating'
            'kurir_rating' => 'required|integer|min:1|max:5', // Nama input harus 'kurir_rating'
            'feedback' => 'nullable|string|max:1000',
        ]);

        $order->product_rating = $validatedData['product_rating'];
        $order->kurir_rating = $validatedData['kurir_rating'];
        $order->feedback = $validatedData['feedback'];
        $order->feedback_submitted_at = now();
        $order->save();

        return redirect()->route('user.orders.show', $order->id)->with('success', 'Terima kasih atas ulasan Anda!');
    }

    /**
     * "Hapus" atau arsipkan pesanan dari riwayat user.
     */
    public function archive(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $order->archived_by_user = true;
        $order->save();

        return redirect()->route('user.orders.index')->with('success', "Riwayat pesanan #{$order->order_number} telah dihapus.");
    }
}
