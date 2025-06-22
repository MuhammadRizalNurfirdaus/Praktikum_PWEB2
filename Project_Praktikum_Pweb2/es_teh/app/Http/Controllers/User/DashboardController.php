<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5) // Ambil 5 pesanan terbaru
            ->get();
        $pendingOrdersCount = Order::where('user_id', $user->id)
            ->whereNotIn('status', ['delivered', 'cancelled', 'failed'])
            ->count();

        return view('user.dashboard', compact('user', 'recentOrders', 'pendingOrdersCount'));
    }
}
