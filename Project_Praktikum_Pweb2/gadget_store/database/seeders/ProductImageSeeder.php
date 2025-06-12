<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa produk yang sudah ada
        // Anda bisa menggunakan ->take(n) atau ->get() untuk semua produk
        $products = Product::take(3)->get(); // Ambil 3 produk pertama sebagai contoh

        if ($products->isEmpty()) {
            $this->command->info('Tidak ada produk untuk ditambahkan gambar. Jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        foreach ($products as $product) {
            // Contoh path gambar dummy, siapkan gambar ini di public/images/products_dummy/
            // atau gunakan URL placeholder
            $dummyImages = [
                'products_dummy/sample-image-1.jpg',
                'products_dummy/sample-image-2.jpg',
                'products_dummy/sample-image-3.jpg',
            ];

            $isFirstImage = true;
            foreach ($dummyImages as $index => $imagePath) {
                // Cek apakah file gambar dummy ada jika Anda menyimpannya secara lokal
                // if (!file_exists(public_path('images/' . $imagePath))) {
                //     $this->command->warn("Gambar dummy tidak ditemukan: " . public_path('images/' . $imagePath));
                //     continue;
                // }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath, // Sesuaikan path jika perlu (misal, jika di storage)
                    'alt_text' => "Gambar " . ($index + 1) . " untuk " . $product->name,
                    'is_primary' => $isFirstImage, // Gambar pertama dijadikan primary
                ]);
                $isFirstImage = false;
            }
            $this->command->info("Menambahkan gambar untuk produk: " . $product->name);
        }
    }
}
