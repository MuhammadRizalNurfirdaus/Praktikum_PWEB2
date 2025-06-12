<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand; // <-- Import model Brand
use Illuminate\Support\Str; // <-- Import Str untuk slug

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Samsung',
            'Apple',
            'Xiaomi',
            'Oppo',
            'Vivo',
            'Realme',
            'Asus',
            'Lenovo',
            'HP',
            'Dell',
            'Acer',
            'MSI'
        ];

        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
                // 'logo_path' => 'path/to/logos/' . Str::slug($brandName) . '.png', // Opsional
            ]);
        }
    }
}
