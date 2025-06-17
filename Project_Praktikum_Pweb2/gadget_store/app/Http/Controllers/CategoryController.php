<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk slug

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::latest()->paginate(10); // Mengambil data kategori
        return view('categories.index', compact('categories')); // Mengirim $categories ke view
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name), // Buat slug jika kosong
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // app/Http/Controllers/CategoryController.php
    public function show(Category $category) // Laravel akan otomatis mencari berdasarkan 'slug'
    {
        $products = $category->products()->latest()->paginate(9);
        return view('categories.show', compact('category', 'products'));
    }

    public function edit(Category $category) // Laravel akan otomatis mencari berdasarkan 'slug'
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) // Laravel akan otomatis mencari berdasarkan 'slug'
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // Jika slug di-provide dari form dan berbeda, gunakan itu.
        // Jika tidak, atau jika nama berubah, generate ulang dari nama.
        $newSlug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        // Cek keunikan slug jika berubah atau nama berubah yang mengakibatkan slug berubah
        if ($category->slug !== $newSlug || $category->name !== $request->name) {
            $originalSlug = $newSlug;
            $count = 1;
            while (Category::whereSlug($newSlug)->where('id', '!=', $category->id)->exists()) {
                $newSlug = "{$originalSlug}-{$count}";
                $count++;
            }
        } else {
            $newSlug = $category->slug; // Gunakan slug lama jika tidak ada perubahan signifikan
        }


        $category->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category) // Laravel akan otomatis mencari berdasarkan 'slug'
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
