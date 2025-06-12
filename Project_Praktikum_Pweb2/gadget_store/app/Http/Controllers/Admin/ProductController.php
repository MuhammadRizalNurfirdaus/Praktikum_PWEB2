<?php

namespace App\Http\Controllers\Admin; // <-- Perhatikan namespace Admin

use App\Http\Controllers\Controller; // <-- Pastikan ini di-use
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar jika diperlukan

class ProductController extends Controller
{
    /**
     * Display a listing of the resource for Admin.
     */
    public function index()
    {
        // Ambil semua produk untuk ditampilkan di halaman admin, bisa dengan paginasi
        $products = Product::with('category', 'brand')->latest()->paginate(10);
        // View ini akan ada di: resources/views/admin/products/index.blade.php
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource (Admin).
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        // View ini akan ada di: resources/views/admin/products/create.blade.php
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage (Admin).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'condition' => 'required|in:baru,bekas,refurbished',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            // 'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Untuk upload gambar utama
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $originalSlug = $validatedData['slug'];
        $count = 1;
        while (Product::where('slug', $validatedData['slug'])->exists()) {
            $validatedData['slug'] = $originalSlug . '-' . $count++;
        }

        $validatedData['is_featured'] = $request->has('is_featured');
        $validatedData['is_active'] = $request->has('is_active');

        $product = Product::create($validatedData);

        // --- Logika Upload Gambar Utama (Contoh Sederhana) ---
        // if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
        //     $path = $request->file('main_image')->store('products', 'public'); // Simpan di storage/app/public/products
        //     $product->images()->create([ // Asumsi relasi 'images' dan model ProductImage sudah ada
        //         'image_path' => $path,
        //         'is_primary' => true,
        //     ]);
        // }
        // --- Akhir Logika Upload Gambar ---

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (Admin - mungkin tidak terlalu sering digunakan, lebih ke edit).
     */
    public function show(Product $product)
    {
        // Biasanya admin langsung ke halaman edit, tapi bisa juga ada halaman show khusus admin.
        // View ini akan ada di: resources/views/admin/products/show.blade.php
        $product->load('category', 'brand', 'images', 'specifications');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource (Admin).
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        // View ini akan ada di: resources/views/admin/products/edit.blade.php
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage (Admin).
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'condition' => 'required|in:baru,bekas,refurbished',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            // 'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->name !== $product->name) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
            $originalSlug = $validatedData['slug'];
            $count = 1;
            while (Product::where('slug', $validatedData['slug'])->where('id', '!=', $product->id)->exists()) {
                $validatedData['slug'] = $originalSlug . '-' . $count++;
            }
        }

        $validatedData['is_featured'] = $request->has('is_featured');
        $validatedData['is_active'] = $request->has('is_active');

        $product->update($validatedData);

        // --- Logika Update/Upload Gambar Utama (Contoh Sederhana) ---
        // Implementasi mirip dengan di method store(), mungkin perlu menghapus gambar lama dulu.
        // --- Akhir Logika Update Gambar ---

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage (Admin).
     */
    public function destroy(Product $product)
    {
        // --- Logika Hapus Gambar Terkait (Contoh) ---
        // Jika produk memiliki banyak gambar di tabel terpisah:
        // foreach ($product->images as $image) {
        //     Storage::disk('public')->delete($image->image_path);
        //     $image->delete();
        // }
        // Jika path gambar utama ada di tabel products (misal kolom 'image'):
        // if ($product->image) {
        //     Storage::disk('public')->delete($product->image);
        // }
        // --- Akhir Logika Hapus Gambar ---

        $product->delete(); // Ini akan melakukan soft delete jika model Product menggunakan trait SoftDeletes

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
