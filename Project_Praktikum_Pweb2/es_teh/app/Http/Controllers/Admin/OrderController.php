<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace ini sesuai

use App\Http\Controllers\Controller;   // Controller dasar Laravel
use App\Models\Order;                  // Model Order Anda
use App\Models\User;                   // Untuk mengambil daftar kurir di method show/update
use Illuminate\Http\Request;           // Untuk mengambil input dari request (filter, search)
use Illuminate\Validation\Rule;        // Diperlukan untuk validasi status
use Illuminate\Support\Facades\Auth;   // Untuk mendapatkan user yang login (misalnya saat logging aktivitas)
use Illuminate\Support\Facades\Log;    // Untuk logging error jika perlu

class OrderController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        // ==========================================================================
        // PASTIKAN TIDAK ADA dd(), dump(), echo, var_dump(), exit(), die()
        // DI SELURUH METHOD INI SEBELUM BARIS `return view(...)` DI BAWAH.
        // INI ADALAH PENYEBAB PALING MUNGKIN DARI MASALAH TAMPILAN ANDA.
        // CARI DENGAN TELITI!
        // ==========================================================================

        $searchQuery = $request->input('search');
        $statusFilter = $request->input('status');
        $itemPerPage = 10;

        $query = Order::with('user');

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('order_number', 'like', "%{$searchQuery}%")
                    ->orWhere('customer_name', 'like', "%{$searchQuery}%")
                    ->orWhereHas('user', function ($subQ) use ($searchQuery) {
                        $subQ->where('name', 'like', "%{$searchQuery}%")
                            ->orWhere('email', 'like', "%{$searchQuery}%");
                    });
            });
        }

        if ($statusFilter && !empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($itemPerPage);

        $orderStatuses = [
            'pending',
            'menunggu_pembayaran_qr',
            'menunggu_pembayaran_va',
            'menunggu_pembayaran_ewallet',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
            'failed',
            'paid'
        ];

        return view('admin.orders.index', compact(
            'orders',
            'orderStatuses',
            'searchQuery',
            'statusFilter'
        ));
    }

    // Pastikan method lain juga bersih dari output debug jika dipanggil sebelum view dirender
    public function show(Order $order)
    {
        $order->load('items.product', 'user', 'kurir');
        $orderStatuses = ['pending', 'menunggu_pembayaran_qr', 'menunggu_pembayaran_va', 'menunggu_pembayaran_ewallet', 'processing', 'shipped', 'delivered', 'cancelled', 'failed', 'paid'];
        $availableKurirs = User::where('role', 'kurir')->orderBy('name')->get();
        return view('admin.orders.show', compact('order', 'orderStatuses', 'availableKurirs'));
    }

    public function update(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $allowedAdminStatuses = [
            'pending',
            'menunggu_pembayaran_qr',
            'menunggu_pembayaran_va',
            'menunggu_pembayaran_ewallet',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
            'failed',
            'paid'
        ];
        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        $oldKurirId = $order->kurir_id;

        // Validasi input
        $validatedData = $request->validate([
            'status' => ['required', 'string', Rule::in($allowedAdminStatuses)],
            'kurir_id' => 'nullable|exists:users,id,role,kurir', // Pastikan kurir_id adalah user dengan role kurir
        ]);

        // Update status pesanan
        $order->status = $validatedData['status'];

        // Update kurir_id jika ada dalam request
        if ($request->exists('kurir_id')) { // Periksa apakah field kurir_id dikirim
            $order->kurir_id = $request->input('kurir_id') == "" ? null : $request->input('kurir_id');
        }
        // Jika field kurir_id tidak ada di request sama sekali, $order->kurir_id tidak akan diubah.

        // === AWAL PERBAIKAN LOGIKA STATUS PEMBAYARAN ===
        // Jika status pesanan diubah menjadi 'shipped', 'delivered', atau 'paid',
        // dan metode pembayarannya BUKAN COD, maka payment_status seharusnya menjadi 'paid'.
        // Untuk COD, payment_status menjadi 'paid' biasanya saat kurir mengonfirmasi 'delivered'.
        if (in_array($order->status, ['shipped', 'delivered', 'paid'])) {
            if ($order->payment_method !== 'COD') {
                $order->payment_status = 'paid';
            } elseif ($order->status === 'delivered' && $order->payment_method === 'COD') {
                // Jika COD dan statusnya 'delivered', baru set payment_status 'paid'
                $order->payment_status = 'paid';
            }
        } elseif (in_array($order->status, ['menunggu_pembayaran_qr', 'menunggu_pembayaran_va', 'menunggu_pembayaran_ewallet'])) {
            // Jika status pesanan kembali ke menunggu pembayaran, pastikan status pembayaran juga sesuai
            if ($order->payment_method === 'QRCODE') $order->payment_status = 'menunggu_qr';
            elseif ($order->payment_method === 'VIRTUAL_ACCOUNT' || str_starts_with($order->payment_method, 'VA ')) $order->payment_status = 'menunggu_va';
            elseif ($order->payment_method === 'EWALLET_DANA') $order->payment_status = 'menunggu_ewallet';
            else $order->payment_status = 'unpaid'; // Fallback jika tidak cocok
        } elseif ($order->status === 'cancelled' || $order->status === 'failed') {
            // Jika pesanan dibatalkan atau gagal, dan pembayaran belum 'paid',
            // mungkin status pembayaran bisa tetap 'unpaid' atau 'failed_payment'.
            // Jika sudah 'paid', mungkin perlu logika refund dan status 'refunded'.
            // Untuk sekarang, kita tidak ubah payment_status jika sudah 'paid'.
            if ($order->payment_status !== 'paid') { // Hanya ubah jika belum lunas
                if ($order->status === 'cancelled') $order->payment_status = 'cancelled_payment'; // Contoh status baru
                if ($order->status === 'failed') $order->payment_status = 'failed_payment'; // Contoh status baru
            }
        } elseif ($order->status === 'pending' && $order->payment_status === 'paid') {
            // Kasus aneh: pesanan pending tapi sudah dibayar? Mungkin perlu investigasi atau reset payment_status.
            // Untuk amannya, jika status order 'pending', pastikan payment_status bukan 'paid' kecuali ada alur khusus.
            // $order->payment_status = 'unpaid'; // Contoh reset
        }
        // === AKHIR PERBAIKAN LOGIKA STATUS PEMBAYARAN ===

        $order->save();

        // Mencatat aktivitas jika ada perubahan signifikan
        if ($oldStatus !== $order->status || $oldPaymentStatus !== $order->payment_status || $oldKurirId != $order->kurir_id) {
            log_activity(
                "Admin memperbarui pesanan #{$order->order_number}. Status: '{$oldStatus}' -> '{$order->status}'. Pembayaran: '{$oldPaymentStatus}' -> '{$order->payment_status}'. Kurir ID: '" . ($oldKurirId ?? 'N/A') . "' -> '" . ($order->kurir_id ?? 'N/A') . "'.",
                $order,
                [
                    'old_order_status' => $oldStatus,
                    'new_order_status' => $order->status,
                    'old_payment_status' => $oldPaymentStatus,
                    'new_payment_status' => $order->payment_status,
                    'old_kurir_id' => $oldKurirId,
                    'new_kurir_id' => $order->kurir_id,
                    'updated_by_role' => Auth::user()->role
                ],
                'Pesanan'
            );
        }
        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Pesanan berhasil diperbarui oleh Admin.');
    }


    /**
     * Admin mengonfirmasi pembayaran QR telah diterima.
     */
    public function confirmQrPayment(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        // Pastikan tidak ada dd() atau output debug di sini
        if ($order->payment_method === 'QRCODE' && $order->payment_status === 'menunggu_qr') {
            $oldStatus = $order->status;
            $oldPaymentStatus = $order->payment_status;
            $order->payment_status = 'paid';
            if ($order->status === 'menunggu_pembayaran_qr') {
                $order->status = 'processing';
            }
            $order->save();
            log_activity("Admin mengonfirmasi pembayaran QR untuk pesanan #{$order->order_number}.", $order, ['old_payment_status' => $oldPaymentStatus, 'new_payment_status' => 'paid', 'old_order_status' => $oldStatus, 'new_order_status' => $order->status], 'Pembayaran');
            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Pembayaran QR untuk pesanan berhasil dikonfirmasi.');
        }
        return redirect()->route('admin.orders.show', $order->id)->with('error', 'Pesanan ini tidak menggunakan metode QR atau pembayaran sudah dikonfirmasi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): \Illuminate\Http\RedirectResponse
    {
        // Pastikan tidak ada dd() atau output debug di sini
        $orderNumber = $order->order_number;
        $orderDetails = $order->toArray();
        if ($order->items()->count() > 0) {
            $order->items()->delete();
        }
        $order->delete();
        log_activity("Admin menghapus pesanan #{$orderNumber}.", null, ['order_details' => $orderDetails], 'Pesanan');
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
    public function printInvoice(Order $order): \Illuminate\View\View
    {
        // Eager load relasi yang diperlukan untuk ditampilkan di struk
        $order->load('items.product', 'user');

        return view('admin.orders.invoice', compact('order'));
        // Kita akan membuat view 'admin.orders.invoice' di langkah berikutnya
    }
}
