<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class, // Harus sebelum ProductSeeder
            ProductSeeder::class,
            ProductImageSeeder::class, // Panggil setelah ProductSeeder jika mengisi gambar produk
            ProductSpecificationSeeder::class, // Panggil setelah ProductSeeder
            AddressSeeder::class, // Panggil setelah UserSeeder
            ReviewSeeder::class, // Panggil setelah UserSeeder dan ProductSeeder
        ]);
    }
}
