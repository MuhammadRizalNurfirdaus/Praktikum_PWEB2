<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $takeCount = 4; // Ambil 4 produk untuk setiap bagian

        // Ambil Produk Terbaru
        $newestProducts = Product::orderBy('created_at', 'desc')
            ->take($takeCount)
            ->get();

        $newestProductIds = $newestProducts->pluck('id')->toArray();

        // Ambil Produk Unggulan/Populer (Acak, dan beda dari yang terbaru)
        $featuredProducts = Product::whereNotIn('id', $newestProductIds)
            ->inRandomOrder()
            ->take($takeCount)
            ->get();

        // Fallback jika produk unggulan kurang dari yang dibutuhkan
        if ($featuredProducts->count() < $takeCount) {
            $existingIds = $featuredProducts->pluck('id')->merge($newestProductIds)->unique()->toArray();
            $additionalNeeded = $takeCount - $featuredProducts->count();
            if ($additionalNeeded > 0) {
                $additionalFeatured = Product::whereNotIn('id', $existingIds)
                    ->inRandomOrder()
                    ->take($additionalNeeded)
                    ->get();
                $featuredProducts = $featuredProducts->merge($additionalFeatured);
            }
        }

        // Fallback final jika produk unggulan masih kosong
        if ($featuredProducts->isEmpty() && Product::count() > 0) {
            $featuredProducts = Product::inRandomOrder()->take($takeCount)->get();
        }

        return view('home', compact('featuredProducts', 'newestProducts'));
    }
}
