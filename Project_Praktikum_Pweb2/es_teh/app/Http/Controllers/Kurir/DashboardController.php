<?php

namespace App\Http\Controllers\Kurir; // Pastikan namespace controller ini benar

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // <--- PASTIKAN IMPORT INI ADA DAN BENAR
use Carbon\Carbon;     // Import Carbon jika Anda menggunakannya untuk filter tanggal

class DashboardController extends Controller
{
    public function index()
    {
        $kurirId = Auth::id(); // Dapatkan ID kurir yang sedang login

        // Pengiriman Aktif: status 'processing' atau 'shipped' DAN ditugaskan ke kurir ini
        $activeShipmentsCount = Order::where('kurir_id', $kurirId) // Sekarang 'Order' akan merujuk ke App\Models\Order
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        // Pengiriman Selesai Hari Ini: status 'delivered' DAN ditugaskan ke kurir ini DAN tanggalnya hari ini
        $completedTodayCount = Order::where('kurir_id', $kurirId) // 'Order' merujuk ke App\Models\Order
            ->where('status', 'delivered')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        return view('kurir.dashboard', compact(
            'activeShipmentsCount',
            'completedTodayCount'
        ));
    }
}
