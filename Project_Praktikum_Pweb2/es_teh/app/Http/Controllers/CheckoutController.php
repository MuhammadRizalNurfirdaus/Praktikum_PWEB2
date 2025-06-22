<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // Jika perlu untuk mengecek stok atau info lain
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        $user = Auth::user();
        return view('checkout.index', compact('cartItems', 'totalPrice', 'user'));
    }

    public function placeOrder(Request $request)
    {
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $user->email,
                'shipping_address' => $validatedData['shipping_address'],
                'customer_phone' => $validatedData['customer_phone'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => 'unpaid',
                'notes' => $validatedData['notes'],
            ]);

            foreach ($cartItems as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'sub_total' => $details['price'] * $details['quantity'],
                ]);
            }

            DB::commit();
            Session::forget('cart');

            // Mencatat aktivitas
            log_activity(
                "Pesanan baru #{$order->order_number} dibuat oleh {$order->customer_name}.",
                $order, // Model Order sebagai subject
                ['total_amount' => $order->total_amount, 'payment_method' => $order->payment_method],
                'Pesanan' // Nama log
            );

            return redirect()->route('home')->with('success', 'Pesanan Anda berhasil dibuat! Nomor Pesanan: ' . $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Gagal: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
    }
}
