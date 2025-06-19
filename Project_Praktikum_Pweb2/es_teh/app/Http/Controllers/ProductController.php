<?php

namespace App\Http\Controllers; // Pastikan namespace ini benar

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk untuk umum.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $itemPerPage = 9; // Jumlah produk per halaman untuk user

        $products = Product::query()
            ->when($searchQuery, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->orderBy('size')
            ->paginate($itemPerPage);

        // PASTIKAN NAMA VIEW DI SINI BENAR
        // Seharusnya 'products.public_index' bukan 'products.index' untuk halaman publik
        return view('products.public_index', compact('products'));
    }

    /**
     * Menampilkan detail satu produk untuk umum.
     */
    public function show(Product $product)
    {
        // PASTIKAN NAMA VIEW DI SINI BENAR
        // Seharusnya 'products.public_show' bukan 'products.show'
        return view('products.public_show', compact('product'));
    }
}
