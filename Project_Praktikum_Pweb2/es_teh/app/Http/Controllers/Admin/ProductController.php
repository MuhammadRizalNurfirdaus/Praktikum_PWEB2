<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace ini benar

use App\Http\Controllers\Controller; // PENTING: Gunakan Controller dasar Laravel
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class ProductController extends Controller // Pastikan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $itemPerPage = 10;

        $products = Product::query()
            ->when($searchQuery, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('size', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($itemPerPage);

        return view('admin.products.index', compact('products'));
    }


    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'product_image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('product_image_file')) {
            // Simpan gambar ke storage/app/public/products
            // store() akan membuat nama file unik secara otomatis
            $path = $request->file('product_image_file')->store('products', 'public');
            $validatedData['image_path'] = $path; // Simpan path relatif ini ke database
        }
        unset($validatedData['product_image_file']); // Hapus dari data yang akan di-create karena sudah di-handle

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'image_action' => 'required|string|in:keep,replace,delete', // Validasi tindakan gambar
            'product_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Hanya required jika action=replace
            'description' => 'nullable|string',
        ]);

        // Tambahkan validasi kondisional untuk product_image_file
        if ($request->input('image_action') === 'replace' && !$request->hasFile('product_image_file')) {
            return back()->withErrors(['product_image_file' => 'Silakan pilih gambar baru untuk diganti.'])->withInput();
        }


        $imagePath = $product->image_path; // Simpan path gambar lama

        if ($request->input('image_action') === 'replace') {
            if ($request->hasFile('product_image_file')) {
                // Hapus gambar lama jika ada
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                // Upload gambar baru
                $newPath = $request->file('product_image_file')->store('products', 'public');
                $validatedData['image_path'] = $newPath;
            }
        } elseif ($request->input('image_action') === 'delete') {
            // Hapus gambar lama jika ada
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $validatedData['image_path'] = null; // Set path gambar menjadi null di database
        } else { // 'keep'
            // Tidak melakukan apa-apa pada image_path, gunakan yang sudah ada
            // Tapi kita perlu menghapusnya dari validatedData agar tidak di-update jika kosong
            unset($validatedData['image_path']);
        }

        unset($validatedData['product_image_file']); // Hapus dari data yang akan di-update
        unset($validatedData['image_action']);    // Hapus dari data yang akan di-update

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // Hapus gambar dari storage sebelum menghapus produk dari database
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
