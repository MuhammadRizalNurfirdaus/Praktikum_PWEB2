<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Auth; // Jika diperlukan

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $statusFilter = $request->input('status');
        $itemPerPage = 10;

        $orders = Order::with('user')
            ->when($searchQuery, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($statusFilter, function ($query, $status) {
                if (!empty($status)) {
                    return $query->where('status', $status);
                }
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate($itemPerPage);

        // PASTIKAN VARIABEL INI DIDEFINISIKAN DAN DIKIRIM KE VIEW
        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'failed', 'paid'];

        return view('admin.orders.index', compact(
            'orders',
            'orderStatuses', // <--- KIRIM VARIABEL INI
            'searchQuery',
            'statusFilter'
        ));
    }

    // ... method show, update, destroy tetap sama seperti sebelumnya ...
    public function show(Order $order)
    {
        $order->load('items.product', 'user', 'kurir');
        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'failed', 'paid']; // Ini juga ada di sini untuk form update
        $availableKurirs = \App\Models\User::where('role', 'kurir')->orderBy('name')->get();
        return view('admin.orders.show', compact('order', 'orderStatuses', 'availableKurirs'));
    }

    public function update(Request $request, Order $order)
    {
        $allowedAdminStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'failed', 'paid'];
        $oldStatus = $order->status;
        $oldKurirId = $order->kurir_id;

        $validatedData = $request->validate([
            'status' => ['required', 'string', Rule::in($allowedAdminStatuses)],
            'kurir_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where(function ($query) {
                return $query->where('role', 'kurir');
            }),],
        ]);
        $order->status = $validatedData['status'];
        if ($request->has('kurir_id')) {
            $order->kurir_id = $request->input('kurir_id') ?: null;
        }
        if (in_array($order->status, ['paid', 'delivered'])) {
            $order->payment_status = 'paid';
        }
        $order->save();
        if ($oldStatus !== $order->status) {
            log_activity("Admin memperbarui status pesanan #{$order->order_number} dari '{$oldStatus}' menjadi '{$order->status}'.", $order, ['old_status' => $oldStatus, 'new_status' => $order->status, 'updated_by_role' => 'admin'], 'Pesanan');
        }
        if ($oldKurirId != $order->kurir_id) {
            $oldKurirName = $oldKurirId ? \App\Models\User::find($oldKurirId)->name : 'Tidak Ada';
            $newKurirName = $order->kurir_id ? $order->kurir->name : 'Tidak Ada';
            log_activity("Admin mengubah penugasan kurir untuk pesanan #{$order->order_number} dari '{$oldKurirName}' menjadi '{$newKurirName}'.", $order, ['old_kurir_id' => $oldKurirId, 'new_kurir_id' => $order->kurir_id], 'Pesanan');
        }
        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        if ($order->items()->count() > 0) {
            $order->items()->delete();
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
