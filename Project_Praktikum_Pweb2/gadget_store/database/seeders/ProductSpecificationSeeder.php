<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSpecification;

class ProductSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all(); // Ambil semua produk

        if ($products->isEmpty()) {
            $this->command->info('Tidak ada produk untuk ditambahkan spesifikasi. Jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        foreach ($products as $product) {
            // Contoh spesifikasi, bisa berbeda untuk tiap tipe produk
            $specifications = [];

            if (stripos($product->name, 'Galaxy S23 Ultra') !== false || stripos($product->name, 'iPhone 15 Pro Max') !== false) {
                // Spesifikasi untuk Smartphone
                $specifications = [
                    ['name' => 'Ukuran Layar', 'value' => '6.8', 'unit' => 'inch'],
                    ['name' => 'RAM', 'value' => '12', 'unit' => 'GB'],
                    ['name' => 'Penyimpanan Internal', 'value' => '256', 'unit' => 'GB'],
                    ['name' => 'Kamera Utama', 'value' => '200', 'unit' => 'MP'],
                    ['name' => 'Baterai', 'value' => '5000', 'unit' => 'mAh'],
                ];
            } elseif (stripos($product->name, 'ROG Zephyrus G16') !== false) {
                // Spesifikasi untuk Laptop
                $specifications = [
                    ['name' => 'Ukuran Layar', 'value' => '16', 'unit' => 'inch'],
                    ['name' => 'Prosesor', 'value' => 'Intel Core i9 Gen 13', 'unit' => null],
                    ['name' => 'RAM', 'value' => '32', 'unit' => 'GB'],
                    ['name' => 'Penyimpanan', 'value' => '1 TB NVMe SSD', 'unit' => null],
                    ['name' => 'Kartu Grafis', 'value' => 'NVIDIA GeForce RTX 4070', 'unit' => null],
                ];
            }

            if (!empty($specifications)) {
                foreach ($specifications as $spec) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'name' => $spec['name'],
                        'value' => $spec['value'],
                        'unit' => $spec['unit'],
                    ]);
                }
                $this->command->info("Menambahkan spesifikasi untuk produk: " . $product->name);
            }
        }
    }
}
