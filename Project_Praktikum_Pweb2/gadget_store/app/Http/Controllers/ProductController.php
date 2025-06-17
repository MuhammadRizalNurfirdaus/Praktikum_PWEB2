<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
// use Illuminate\Support\Facades\Storage; // Tidak kita gunakan jika langsung ke public

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get(); // Ambil semua kategori
        return view('products.create', compact('categories')); // Kirim $categories ke view

    }

    public function store(Request $request) // Pastikan category_id divalidasi dan disimpan
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|string', // Validasi sebagai string dulu
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Bersihkan format harga
        $price = (float) str_replace('.', '', $validated['price']); // Hapus titik, ubah ke float/int
        if (!is_numeric($price) || $price < 0) {
            // Tambahkan error manual jika setelah dibersihkan ternyata bukan angka valid
            return back()->withErrors(['price' => 'Format harga tidak valid.'])->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            // ... (logika upload gambar)
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $price, // Gunakan harga yang sudah dibersihkan
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get(); // Ambil semua kategori
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) // Pastikan category_id divalidasi dan disimpan
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|string', // Validasi sebagai string dulu
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Bersihkan format harga
        $price = (float) str_replace('.', '', $validated['price']); // Hapus titik, ubah ke float/int
        if (!is_numeric($price) || $price < 0) {
            // Tambahkan error manual jika setelah dibersihkan ternyata bukan angka valid
            return back()->withErrors(['price' => 'Format harga tidak valid.'])->withInput();
        }
        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                $oldImagePath = public_path($product->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $price, // Gunakan harga yang sudah dibersihkan
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'image_path' => $imagePath,
        ]);
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            $imagePathToDelete = public_path($product->image_path);
            if (file_exists($imagePathToDelete)) {
                unlink($imagePathToDelete);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
