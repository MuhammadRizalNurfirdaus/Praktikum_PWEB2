<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\ContactMessage;
use App\Models\ActivityLog; // <--- IMPORT ActivityLog
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk filter tanggal

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $newOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::whereIn('status', ['delivered', 'paid'])->sum('total_amount');
        $newContactMessagesCount = ContactMessage::where('status', 'Baru')->count();

        // Mengambil semua aktivitas dari 1 bulan terakhir
        $recentActivities = ActivityLog::with(['subject', 'causer']) // Eager load relasi
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->take(20) // Batasi jumlah yang ditampilkan di dashboard, misal 20
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'newOrdersCount',
            'totalRevenue',
            'recentActivities',
            'newContactMessagesCount'
        ));
    }
}
