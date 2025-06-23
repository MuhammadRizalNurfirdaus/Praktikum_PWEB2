<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // Untuk validasi produk atau pengurangan stok
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule; // Untuk validasi 'in'

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Pastikan $cartItems SELALU didefinisikan, defaultnya array kosong
        $cartItems = Session::get('cart', []);

        // Redirect jika keranjang benar-benar kosong SEBELUM mencoba menghitung total atau mengirim ke view
        if (empty($cartItems) && !session()->has('success_with_qr') && !session()->has('success_with_va') && !session()->has('success_with_ewallet')) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu untuk checkout.');
        }

        $totalPrice = 0;
        if (is_array($cartItems)) { // Hanya hitung jika $cartItems adalah array
            foreach ($cartItems as $item) {
                if (isset($item['price']) && isset($item['quantity'])) {
                    $totalPrice += $item['price'] * $item['quantity'];
                }
            }
        }

        $user = Auth::user();

        // SELALU kirim $cartItems dan $totalPrice, meskipun $cartItems mungkin kosong
        // jika ada pesan sukses QR/VA/Ewallet yang perlu ditampilkan di halaman checkout.
        return view('checkout.index', compact('cartItems', 'totalPrice', 'user'));
    }

    /**
     * Process the order placement.
     */
    public function placeOrder(Request $request): \Illuminate\Http\RedirectResponse
    {
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi input dari form checkout
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:1000',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => ['required', 'string', Rule::in(['COD', 'QRCODE', 'VIRTUAL_ACCOUNT', 'EWALLET_DANA'])],
            'virtual_account_bank' => ['required_if:payment_method,VIRTUAL_ACCOUNT', 'nullable', 'string', Rule::in(['BCA', 'Mandiri'])],
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user(); // User yang sedang login
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $totalAmount += $item['price'] * $item['quantity'];
            } else {
                Log::warning('Invalid cart item structure during checkout.', ['item' => $item]);
                return redirect()->route('cart.index')->with('error', 'Ada masalah dengan item di keranjang Anda. Silakan coba lagi.');
            }
        }

        if ($totalAmount <= 0 && count($cartItems) > 0) {
            Log::error('Checkout attempt with zero or negative total amount.', ['cart' => $cartItems, 'total' => $totalAmount]);
            return redirect()->route('cart.index')->with('error', 'Total pesanan tidak valid. Silakan periksa keranjang Anda.');
        }

        DB::beginTransaction(); // Mulai transaksi database

        try {
            $orderStatus = 'pending'; // Default status pesanan
            $paymentStatus = 'unpaid'; // Default status pembayaran
            $paymentMethodDetails = $validatedData['payment_method']; // Simpan metode utama yang dipilih user

            // Sesuaikan status berdasarkan metode pembayaran
            if ($validatedData['payment_method'] === 'QRCODE') {
                $orderStatus = 'menunggu_pembayaran_qr';
                $paymentStatus = 'menunggu_qr';
                // paymentMethodDetails tetap 'QRCODE'
            } elseif ($validatedData['payment_method'] === 'VIRTUAL_ACCOUNT') {
                $orderStatus = 'menunggu_pembayaran_va';
                $paymentStatus = 'menunggu_va';
                $paymentMethodDetails = 'VA ' . $validatedData['virtual_account_bank']; // e.g., "VA BCA"
            } elseif ($validatedData['payment_method'] === 'EWALLET_DANA') {
                $orderStatus = 'menunggu_pembayaran_ewallet';
                $paymentStatus = 'menunggu_ewallet';
                $paymentMethodDetails = 'E-Wallet DANA';
            }
            // Untuk COD, status tetap 'pending' dan 'unpaid'

            // Buat record Order baru
            $order = Order::create([
                'user_id' => $user->id,
                // 'order_number' akan di-generate otomatis oleh boot method di model Order
                'total_amount' => $totalAmount,
                'status' => $orderStatus,
                'customer_name' => $validatedData['customer_name'],
                'customer_email' => $validatedData['customer_email'],
                'shipping_address' => $validatedData['shipping_address'],
                'customer_phone' => $validatedData['customer_phone'],
                'payment_method' => $paymentMethodDetails, // Menyimpan detail metode pembayaran
                'payment_status' => $paymentStatus,
                'notes' => $validatedData['notes'],
            ]);

            // Simpan setiap item di keranjang sebagai OrderItem
            foreach ($cartItems as $productId => $details) {
                $product = Product::find($productId);
                if (!$product) {
                    DB::rollBack();
                    Log::error("Produk dengan ID {$productId} tidak ditemukan saat checkout untuk order #{$order->order_number}");
                    return redirect()->route('cart.index')->with('error', "Salah satu produk di keranjang Anda tidak lagi tersedia. Silakan perbarui keranjang Anda.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'sub_total' => $details['price'] * $details['quantity'],
                ]);
            }

            DB::commit(); // Jika semua query berhasil, commit transaksi
            Session::forget('cart'); // Kosongkan keranjang belanja di session

            // Mencatat aktivitas
            log_activity(
                "Pesanan baru #{$order->order_number} dibuat oleh {$order->customer_name} via {$order->payment_method}.",
                $order,
                [
                    'total_amount' => $order->total_amount,
                    'payment_method' => $order->payment_method,
                    'va_bank' => $validatedData['virtual_account_bank'] ?? null // Simpan info bank VA jika ada
                ],
                'Pesanan'
            );

            // Redirect berdasarkan metode pembayaran untuk menampilkan instruksi/info spesifik
            if ($validatedData['payment_method'] === 'QRCODE') {
                return redirect()->route('checkout.index')
                    ->with('success_with_qr', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran Anda menggunakan QR Code.')
                    ->with('order_number_for_qr', $order->order_number)
                    ->with('order_id_for_qr', $order->id);
            } elseif ($validatedData['payment_method'] === 'VIRTUAL_ACCOUNT') {
                return redirect()->route('checkout.index')
                    ->with('success_with_va', "Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran ke Virtual Account yang ditampilkan.")
                    ->with('va_bank_for_display', $validatedData['virtual_account_bank'])
                    ->with('va_number_for_display', ($validatedData['virtual_account_bank'] === 'BCA' ? '88081234567890' : '99091234567890')) // Nomor VA Simulasi
                    ->with('total_amount_for_display', $order->total_amount)
                    ->with('order_number_for_display', $order->order_number)
                    ->with('order_id_for_display', $order->id);
            } elseif ($validatedData['payment_method'] === 'EWALLET_DANA') {
                return redirect()->route('checkout.index')
                    ->with('success_with_ewallet', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran Anda via DANA.')
                    ->with('total_amount_for_display', $order->total_amount)
                    ->with('order_number_for_display', $order->order_number)
                    ->with('order_id_for_display', $order->id);
            }

            // Default redirect untuk COD atau metode lain yang tidak butuh tampilan khusus setelah order
            // Biasanya akan diarahkan ke halaman detail pesanan user atau halaman sukses.
            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Pesanan Anda dengan metode Bayar di Tempat (COD) berhasil dibuat! Nomor Pesanan: ' . $order->order_number);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Validasi checkout gagal: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pembuatan Pesanan Gagal: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan teknis saat memproses pesanan Anda. Silakan coba lagi nanti atau hubungi dukungan.');
        }
    }
}
