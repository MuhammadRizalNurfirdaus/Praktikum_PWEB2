<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $takeCount = 3; // Jumlah produk yang ingin ditampilkan per bagian, sesuaikan dengan screenshot Anda

        // Ambil Produk Terbaru
        $newestProducts = Product::orderBy('created_at', 'desc')
            ->take($takeCount)
            ->get();

        // Ambil Produk Unggulan/Populer (Acak, dan coba bedakan dari yang terbaru)
        // Dapatkan ID dari produk terbaru untuk dikecualikan
        $newestProductIds = $newestProducts->pluck('id')->toArray();

        $featuredProducts = Product::whereNotIn('id', $newestProductIds) // Kecualikan produk terbaru
            ->inRandomOrder()
            ->take($takeCount)
            ->get();

        // Jika produk unggulan masih kurang dari $takeCount (karena semua produk adalah produk baru),
        // maka ambil produk acak saja tanpa pengecualian, tapi pastikan tidak duplikat persis
        if ($featuredProducts->count() < $takeCount) {
            // Ambil produk acak tambahan yang belum ada di $featuredProducts dan $newestProducts
            $existingIds = $featuredProducts->pluck('id')->merge($newestProductIds)->unique()->toArray();
            $additionalFeaturedNeeded = $takeCount - $featuredProducts->count();

            if ($additionalFeaturedNeeded > 0) {
                $additionalFeatured = Product::whereNotIn('id', $existingIds)
                    ->inRandomOrder()
                    ->take($additionalFeaturedNeeded)
                    ->get();
                $featuredProducts = $featuredProducts->merge($additionalFeatured);
            }
        }

        // Jika setelah semua usaha produk unggulan masih kosong (misal total produk <= $takeCount dan semua baru)
        // kita bisa isi dengan produk terbaru juga atau produk acak tanpa filter
        if ($featuredProducts->isEmpty() && Product::count() > 0) {
            $featuredProducts = Product::inRandomOrder()->take($takeCount)->get();
        }


        return view('home', compact('featuredProducts', 'newestProducts'));
    }
}
