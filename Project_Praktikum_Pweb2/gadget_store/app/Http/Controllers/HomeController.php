<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- Import model Product
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil produk yang di-featured dan aktif, batasi jumlahnya
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with('brand', 'category') // Eager load relasi untuk efisiensi
            ->take(4) // Ambil 4 produk unggulan
            ->get();

        // Ambil produk terbaru yang aktif, batasi jumlahnya
        $latestProducts = Product::where('is_active', true)
            ->with('brand', 'category')
            ->latest() // Urutkan berdasarkan created_at terbaru
            ->take(8)  // Ambil 8 produk terbaru
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
        ]);
    }
}
