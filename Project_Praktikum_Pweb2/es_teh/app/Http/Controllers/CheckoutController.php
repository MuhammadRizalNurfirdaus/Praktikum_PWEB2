<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $cartItems = Session::get('cart', []);

        // Hanya redirect jika keranjang kosong DAN tidak ada pesan sukses khusus yang perlu ditampilkan
        if (empty($cartItems) && !session()->has('success_with_qr') && !session()->has('success_with_va') && !session()->has('success_with_ewallet')) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $totalPrice = 0;
        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                $totalPrice += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            }
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Cek jika yang login adalah admin, gunakan view checkout POS
        if ($user && $user->isAdmin()) {
            return view('checkout.admin_pos', compact('cartItems', 'totalPrice', 'user'));
        }

        // Untuk user biasa, gunakan view checkout standar
        return view('checkout.index', compact('cartItems', 'totalPrice', 'user'));
    }

    /**
     * Process the order placement.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request): RedirectResponse
    {
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        if ($totalAmount <= 0 && count($cartItems) > 0) {
            return redirect()->route('cart.index')->with('error', 'Total pesanan tidak valid.');
        }

        DB::beginTransaction(); // Mulai transaksi database
        try {
            $order = null;
            $validatedData = [];

            // --- Logika untuk ADMIN (Transaksi POS) ---
            if ($user->isAdmin()) {
                $validatedData = $request->validate([
                    'customer_name' => 'required|string|max:255',
                    'notes' => 'nullable|string|max:1000',
                ]);

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'status' => 'delivered',
                    'payment_status' => 'paid',
                    'payment_method' => 'Cash (POS)',
                    'customer_name' => $validatedData['customer_name'],
                    'customer_email' => 'pos@' . request()->getHost(),
                    'shipping_address' => 'Transaksi Langsung di Toko',
                    'customer_phone' => 'N/A',
                    'notes' => $validatedData['notes'],
                ]);
            }
            // --- Logika untuk USER BIASA (Pengiriman) ---
            else {
                $validatedData = $request->validate([
                    'customer_name' => 'required|string|max:255',
                    'customer_email' => 'required|email|max:255',
                    'shipping_address' => 'required|string|max:1000',
                    'customer_phone' => 'required|string|max:20',
                    'payment_method' => ['required', 'string', Rule::in(['COD', 'QRCODE', 'VIRTUAL_ACCOUNT', 'EWALLET_DANA'])],
                    'virtual_account_bank' => ['required_if:payment_method,VIRTUAL_ACCOUNT', 'nullable', 'string', Rule::in(['BCA', 'Mandiri'])],
                    'notes' => 'nullable|string|max:1000',
                ]);

                $orderStatus = 'pending';
                $paymentStatus = 'unpaid';
                $paymentMethodDetails = $validatedData['payment_method'];

                if ($validatedData['payment_method'] === 'QRCODE') {
                    $orderStatus = 'menunggu_pembayaran_qr';
                    $paymentStatus = 'menunggu_qr';
                } elseif ($validatedData['payment_method'] === 'VIRTUAL_ACCOUNT') {
                    $orderStatus = 'menunggu_pembayaran_va';
                    $paymentStatus = 'menunggu_va';
                    $paymentMethodDetails = 'VA ' . $validatedData['virtual_account_bank'];
                } elseif ($validatedData['payment_method'] === 'EWALLET_DANA') {
                    $orderStatus = 'menunggu_pembayaran_ewallet';
                    $paymentStatus = 'menunggu_ewallet';
                    $paymentMethodDetails = 'E-Wallet DANA';
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'status' => $orderStatus,
                    'customer_name' => $validatedData['customer_name'],
                    'customer_email' => $validatedData['customer_email'],
                    'shipping_address' => $validatedData['shipping_address'],
                    'customer_phone' => $validatedData['customer_phone'],
                    'payment_method' => $paymentMethodDetails,
                    'payment_status' => $paymentStatus,
                    'notes' => $validatedData['notes'],
                ]);
            }

            // Memastikan $order berhasil dibuat sebelum melanjutkan
            if (!$order) {
                throw new \Exception("Gagal membuat data pesanan utama.");
            }

            // --- Logika Pembuatan Order Items (Berlaku untuk SEMUA peran) ---
            foreach ($cartItems as $productId => $details) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$productId} tidak ditemukan saat proses checkout.");
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'sub_total' => $details['price'] * $details['quantity'],
                ]);
            }

            DB::commit(); // Menyimpan semua perubahan ke database
            Session::forget('cart'); // Mengosongkan keranjang

            // Mencatat aktivitas setelah semuanya berhasil
            log_activity(
                "Pesanan baru #{$order->order_number} dibuat oleh {$user->name} via {$order->payment_method}.",
                $order,
                ['total_amount' => $order->total_amount, 'payment_method' => $order->payment_method],
                'Pesanan'
            );

            // --- Logika Redirect Setelah Sukses (berdasarkan peran) ---
            if ($user->isAdmin()) {
                return redirect()->route('admin.orders.show', $order->id)->with('success', 'Transaksi langsung berhasil dibuat!');
            } else {
                // Redirect untuk user biasa berdasarkan metode pembayaran
                if ($order->payment_method === 'QRCODE') {
                    return redirect()->route('checkout.index')
                        ->with('success_with_qr', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran Anda menggunakan QR Code.')
                        ->with('order_number_for_qr', $order->order_number)
                        ->with('order_id_for_qr', $order->id);
                } elseif (str_starts_with($order->payment_method, 'VA ')) {
                    return redirect()->route('checkout.index')
                        ->with('success_with_va', "Pesanan Anda berhasil dibuat! Silakan lakukan pembayaran ke Virtual Account yang ditampilkan.")
                        ->with('va_bank_for_display', $validatedData['virtual_account_bank'])
                        ->with('va_number_for_display', ($validatedData['virtual_account_bank'] === 'BCA' ? '88081234567890' : '99091234567890'))
                        ->with('total_amount_for_display', $order->total_amount)
                        ->with('order_number_for_display', $order->order_number)
                        ->with('order_id_for_display', $order->id);
                } elseif ($order->payment_method === 'E-Wallet DANA') {
                    return redirect()->route('checkout.index')
                        ->with('success_with_ewallet', 'Pesanan Anda berhasil dibuat! Silakan selesaikan pembayaran Anda via DANA.')
                        ->with('total_amount_for_display', $order->total_amount)
                        ->with('order_number_for_display', $order->order_number)
                        ->with('order_id_for_display', $order->id);
                }

                // Default redirect untuk COD
                return redirect()->route('user.orders.show', $order->id)
                    ->with('success', 'Pesanan Anda dengan metode Bayar di Tempat (COD) berhasil dibuat! Nomor Pesanan: ' . $order->order_number);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Batalkan transaksi jika validasi gagal
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error lain
            Log::error('Pembuatan Pesanan Gagal: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan teknis saat memproses pesanan Anda. Silakan coba lagi nanti.');
        }
    }
}
