<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk membuat slug
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar

class ProductController extends Controller
{
    /**
     * Display a listing of the resource for public view.
     */
    public function index(Request $request)
    {
        $productsQuery = Product::where('is_active', true)
            ->with('brand', 'category', 'images');

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $productsQuery->where('category_id', $category->id);
            }
        }

        if ($request->filled('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $productsQuery->where('brand_id', $brand->id);
            }
        }

        if ($request->filled('sort')) {
            if ($request->sort == 'price_asc') {
                $productsQuery->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_desc') {
                $productsQuery->orderBy('price', 'desc');
            } elseif ($request->sort == 'latest') {
                $productsQuery->latest();
            }
        } else {
            $productsQuery->latest(); // Default sorting
        }

        $products = $productsQuery->paginate(12);
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Display the specified resource for public view.
     */
    public function show(Product $product)
    {
        // Admin boleh lihat produk non-aktif jika sudah login dan adalah admin
        if (!$product->is_active && !(auth()->check() && auth()->user()->role === 'admin')) {
            abort(404);
        }

        $product->load(['brand', 'category', 'images', 'specifications', 'reviews.user']);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('images')
            ->take(4)
            ->get();
        return view('products.show', compact('product', 'relatedProducts'));
    }

    // --- CRUD METHODS (SANGAT DIREKOMENDASIKAN DIPINDAH KE ADMIN CONTROLLER TERPISAH) ---

    /**
     * Show the form for creating a new resource.
     * (Admin)
     */
    public function create()
    {
        // Asumsi view ini ada di: resources/views/admin/products/create.blade.php
        // Dan rute ini dilindungi oleh middleware admin
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     * (Admin)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'short_description' => 'nullable|string|max:500', // Batasi panjang jika perlu
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'condition' => 'required|in:baru,bekas,refurbished',
            // 'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $originalSlug = $validatedData['slug'];
        $count = 1;
        while (Product::where('slug', $validatedData['slug'])->exists()) {
            $validatedData['slug'] = $originalSlug . '-' . $count++;
        }

        $validatedData['is_featured'] = $request->boolean('is_featured'); // Gunakan $request->boolean()
        $validatedData['is_active'] = $request->boolean('is_active');     // Gunakan $request->boolean()

        $product = Product::create($validatedData);

        // --- Logika Upload Gambar Utama (Contoh Sederhana) ---
        // if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
        //     $path = $request->file('main_image')->store('products', 'public');
        //     $product->images()->create([
        //         'image_path' => $path,
        //         'is_primary' => true,
        //     ]);
        // }
        // --- Akhir Logika Upload Gambar ---

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }


    /**
     * Show the form for editing the specified resource.
     * (Admin)
     */
    public function edit(Product $product) // Route Model Binding
    {
        // Asumsi view ini ada di: resources/views/admin/products/edit.blade.php
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     * (Admin)
     */
    public function update(Request $request, Product $product) // Route Model Binding
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
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'condition' => 'required|in:baru,bekas,refurbished',
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

        $validatedData['is_featured'] = $request->boolean('is_featured');
        $validatedData['is_active'] = $request->boolean('is_active');

        $product->update($validatedData);

        // --- Logika Update/Upload Gambar Utama (Contoh Sederhana) ---
        // Implementasi mirip dengan di method store(), mungkin perlu menghapus gambar lama dulu.
        // --- Akhir Logika Update Gambar ---

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * (Admin)
     */
    public function destroy(Product $product) // Route Model Binding
    {
        // --- Logika Hapus Gambar Terkait (Contoh Sederhana) ---
        // foreach ($product->images as $image) {
        //     Storage::disk('public')->delete($image->image_path);
        //     $image->delete();
        // }
        // --- Akhir Logika Hapus Gambar ---

        $product->delete(); // Akan melakukan soft delete jika model Product menggunakan trait SoftDeletes

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
