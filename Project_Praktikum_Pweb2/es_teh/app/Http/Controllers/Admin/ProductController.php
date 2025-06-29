<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // PENTING untuk manajemen file
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $products = Product::query()
            ->when($searchQuery, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('id') // Urutkan berdasarkan ID atau nama
            ->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'product_image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('product_image_file')) {
            $path = $request->file('product_image_file')->store('products', 'public');
            $validatedData['image_path'] = $path; // Simpan path relatif ini ke database
        }
        unset($validatedData['product_image_file']);

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        // Anda bisa membuat view detail produk admin jika perlu
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'image_action' => 'required|string|in:keep,replace,delete',
            'product_image_file' => 'required_if:image_action,replace|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->input('image_action') === 'replace') {
            if ($request->hasFile('product_image_file')) {
                // Hapus gambar lama jika ada
                if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }
                // Upload gambar baru
                $path = $request->file('product_image_file')->store('products', 'public');
                $validatedData['image_path'] = $path;
            }
        } elseif ($request->input('image_action') === 'delete') {
            // Hapus gambar lama jika ada
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validatedData['image_path'] = null; // Set path gambar menjadi null
        }

        unset($validatedData['product_image_file']);
        unset($validatedData['image_action']);

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
