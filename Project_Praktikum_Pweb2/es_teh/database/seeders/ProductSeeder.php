<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // Pastikan model Product di-import

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::updateOrCreate(
            ['name' => 'Es Teh Poci Aji Manis', 'size' => 'Besar'], // Kriteria pencarian
            [ // Data yang akan diisi atau diupdate
                'price' => 5000,
                'image_path' => 'images/ukuran_besar.png',
                'description' => 'Es Teh Poci Aji Manis segar ukuran besar nikmat.'
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Es Teh Poci Aji Manis', 'size' => 'Sedang'], // Kriteria pencarian
            [ // Data yang akan diisi atau diupdate
                'price' => 3000,
                'image_path' => 'images/ukuran_kecil.png', // Sesuaikan dengan nama file gambar Anda
                'description' => 'Es Teh Poci Aji Manis segar ukuran sedang pas.'
            ]
        );

        // Anda bisa tambahkan produk lain jika ada
        // Product::updateOrCreate(
        //     ['name' => 'Teh Tarik Spesial', 'size' => 'Sedang'],
        //     [
        //         'price' => 8000,
        //         'image_path' => 'images/teh_tarik.png', // Ganti dengan path gambar yang sesuai
        //         'description' => 'Teh Tarik dengan rasa otentik.'
        //     ]
        // );
    }
}
