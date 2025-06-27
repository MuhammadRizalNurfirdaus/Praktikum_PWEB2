<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Order; // <--- PASTIKAN IMPORT INI ADA DAN BENAR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $kurirId = Auth::id();
        $itemPerPage = 10;
        // Filter berdasarkan status jika ada parameter 'status' di request
        $statusFilter = $request->input('status');
        $dateFilter = $request->input('date_filter'); // Untuk filter tanggal 'today'

        $orders = Order::with('user') // 'Order' merujuk ke App\Models\Order
            ->where('kurir_id', $kurirId) // Hanya pesanan yang ditugaskan ke kurir ini
            ->when($statusFilter, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dateFilter === 'today', function ($query) {
                return $query->whereDate('updated_at', Carbon::today()); // Asumsi Carbon sudah diimport di atas jika digunakan
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($itemPerPage);

        return view('kurir.orders.index', compact('orders'));
    }

    public function show(Order $order) // 'Order' di sini adalah route model binding ke App\Models\Order
    {
        // Validasi apakah order ini milik kurir yang login atau statusnya relevan
        if ($order->kurir_id !== Auth::id() && !in_array($order->status, ['processing', 'shipped', 'delivered', 'failed'])) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini atau status tidak sesuai.');
        }

        $order->load('items.product', 'user');
        $kurirAllowedStatuses = ['shipped', 'delivered', 'failed']; // Status yang boleh diubah kurir
        return view('kurir.orders.show', compact('order', 'kurirAllowedStatuses'));
    }

    /**
     * Update status pesanan oleh Kurir.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $kurirAllowedUpdateStatuses = ['shipped', 'delivered', 'failed'];

        $validatedData = $request->validate([
            'status' => ['required', 'string', Rule::in($kurirAllowedUpdateStatuses)],
            // Validasi bukti pengiriman: wajib jika statusnya 'delivered'
            'proof_of_delivery' => 'required_if:status,delivered|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // TODO: Tambahkan validasi otorisasi untuk memastikan kurir ini yang ditugaskan
        // if ($order->kurir_id !== Auth::id()) {
        //     return redirect()->back()->with('error', 'Anda tidak dapat mengubah status pesanan ini.');
        // }

        $oldStatus = $order->status;
        $order->status = $validatedData['status'];

        if ($order->status === 'delivered') {
            $order->payment_status = 'paid'; // Asumsi 'delivered' juga berarti 'paid' (untuk COD)

            if ($request->hasFile('proof_of_delivery')) {
                // Hapus bukti lama jika ada (untuk kasus update ulang)
                if ($order->proof_of_delivery && Storage::disk('public')->exists($order->proof_of_delivery)) {
                    Storage::disk('public')->delete($order->proof_of_delivery);
                }
                // Simpan file baru ke storage/app/public/proofs
                $path = $request->file('proof_of_delivery')->store('proofs', 'public');
                $order->proof_of_delivery = $path; // Simpan path ke database
            }
        }
        $order->save();

        log_activity(
            "Kurir memperbarui status pesanan #{$order->order_number} dari '{$oldStatus}' menjadi '{$order->status}'.",
            $order,
            ['old_status' => $oldStatus, 'new_status' => $order->status],
            'Pesanan'
        );

        return redirect()->route('kurir.orders.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
