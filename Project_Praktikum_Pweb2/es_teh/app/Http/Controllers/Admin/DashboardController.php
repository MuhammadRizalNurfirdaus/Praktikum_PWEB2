<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Import Product model
use App\Models\User;    // Import User model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        // Anda bisa tambahkan query lain untuk pesanan baru, pendapatan, dll.
        // $newOrders = 0; // Contoh
        // $totalRevenue = 0; // Contoh

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers'
            // 'newOrders',
            // 'totalRevenue'
        ));
    }
}
