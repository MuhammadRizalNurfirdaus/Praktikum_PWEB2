<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // <-- Import model Product
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kategori dan brand yang sudah ada
        // Pastikan CategorySeeder dan BrandSeeder sudah dijalankan sebelumnya
        $smartphoneCategory = Category::where('slug', 'smartphones')->first();
        $laptopCategory = Category::where('slug', 'laptops')->first();

        $samsungBrand = Brand::where('slug', 'samsung')->first();
        $appleBrand = Brand::where('slug', 'apple')->first();
        $asusBrand = Brand::where('slug', 'asus')->first();

        if ($smartphoneCategory && $samsungBrand) {
            Product::create([
                'category_id' => $smartphoneCategory->id,
                'brand_id' => $samsungBrand->id,
                'name' => 'Samsung Galaxy S23 Ultra',
                'slug' => Str::slug('Samsung Galaxy S23 Ultra'),
                'sku' => 'SM-S23U-BLK',
                'short_description' => 'Smartphone flagship dengan kamera luar biasa.',
                'long_description' => 'Detail lengkap Samsung Galaxy S23 Ultra...',
                'price' => 18999000,
                'stock_quantity' => 50,
                'is_featured' => true,
                'is_active' => true,
                'condition' => 'baru',
                'warranty_info' => 'Garansi Resmi Samsung Indonesia 1 Tahun',
            ]);
        }

        if ($smartphoneCategory && $appleBrand) {
            Product::create([
                'category_id' => $smartphoneCategory->id,
                'brand_id' => $appleBrand->id,
                'name' => 'iPhone 15 Pro Max',
                'slug' => Str::slug('iPhone 15 Pro Max'),
                'sku' => 'IP15-PM-TIT',
                'short_description' => 'Kinerja super cepat dan desain premium.',
                'long_description' => 'Detail lengkap iPhone 15 Pro Max...',
                'price' => 24999000,
                'stock_quantity' => 30,
                'is_featured' => true,
                'is_active' => true,
                'condition' => 'baru',
                'warranty_info' => 'Garansi Resmi Apple Indonesia 1 Tahun',
            ]);
        }

        if ($laptopCategory && $asusBrand) {
            Product::create([
                'category_id' => $laptopCategory->id,
                'brand_id' => $asusBrand->id,
                'name' => 'ASUS ROG Zephyrus G16',
                'slug' => Str::slug('ASUS ROG Zephyrus G16'),
                'sku' => 'AS-ROG-ZG16',
                'short_description' => 'Laptop gaming tipis dengan performa tinggi.',
                'long_description' => 'Detail lengkap ASUS ROG Zephyrus G16...',
                'price' => 28999000,
                'stock_quantity' => 20,
                'is_featured' => false,
                'is_active' => true,
                'condition' => 'baru',
                'warranty_info' => 'Garansi Resmi ASUS Indonesia 2 Tahun',
            ]);
        }

        // Tambahkan lebih banyak produk dummy di sini
        // Anda juga bisa menggunakan Product::factory(jumlah)->create(); jika sudah setup factory
    }
}
