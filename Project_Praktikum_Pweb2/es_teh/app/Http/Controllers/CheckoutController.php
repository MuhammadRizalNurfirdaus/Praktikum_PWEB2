<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
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

        // Ambil data user yang sedang login untuk pre-fill form (opsional)
        $user = Auth::user();

        return view('checkout.index', compact('cartItems', 'totalPrice', 'user'));
    }

    // Memproses pesanan (akan diimplementasikan lebih lanjut)
    public function placeOrder(Request $request)
    {
        // Validasi input form checkout (nama, alamat, telepon, dll.)
        // Ambil item dari keranjang session
        // Buat record di tabel 'orders'
        // Buat record di tabel 'order_items' untuk setiap item
        // Kosongkan keranjang session
        // Redirect ke halaman sukses atau halaman detail pesanan

        // Untuk sekarang, kita hanya redirect dengan pesan sukses
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // --- AWAL LOGIKA PENYIMPANAN PESANAN (SANGAT DASAR) ---
        $user = Auth::user();
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Validasi sederhana untuk input form nanti
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'customer_phone' => 'required|string|max:20',
            // 'payment_method' => 'required|string', // Jika ada pilihan metode pembayaran
        ]);

        try {
            DB::beginTransaction(); // Mulai transaksi database

            $order = Order::create([
                'user_id' => $user->id,
                // 'order_number' => generate_order_number(), // Sudah di-handle di model Order
                'total_amount' => $totalAmount,
                'status' => 'pending', // Status awal pesanan
                'customer_name' => $request->input('customer_name'),
                'customer_email' => $user->email, // Ambil dari user yang login
                'shipping_address' => $request->input('shipping_address'),
                'customer_phone' => $request->input('customer_phone'),
                'payment_method' => $request->input('payment_method', 'COD'), // Contoh default
                'payment_status' => 'unpaid',
                'notes' => $request->input('notes'),
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

            DB::commit(); // Jika semua berhasil, commit transaksi

            Session::forget('cart'); // Kosongkan keranjang setelah pesanan berhasil

            // Redirect ke halaman sukses atau detail pesanan (belum dibuat)
            return redirect()->route('home')->with('success', 'Pesanan Anda berhasil dibuat! Nomor Pesanan: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan transaksi
            // Log errornya
            Log::error('Order Gagal: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
        // --- AKHIR LOGIKA PENYIMPANAN PESANAN ---
    }
}