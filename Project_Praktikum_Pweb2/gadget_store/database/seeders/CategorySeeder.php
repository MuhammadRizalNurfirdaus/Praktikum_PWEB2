<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // <-- Import model Category
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = [
            ['name' => 'Smartphones', 'description' => 'Berbagai macam smartphone terbaru.'],
            ['name' => 'Laptops', 'description' => 'Laptop untuk produktivitas dan gaming.'],
            ['name' => 'Tablets', 'description' => 'Tablet serbaguna untuk hiburan dan kerja.'],
            ['name' => 'Smartwatches', 'description' => 'Jam tangan pintar dengan berbagai fitur.'],
            ['name' => 'Aksesoris', 'description' => 'Aksesoris pendukung gadget Anda.'],
        ];

        $parentIds = [];
        foreach ($mainCategories as $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
            ]);
            $parentIds[Str::slug($cat['name'])] = $category->id;
        }

        // Contoh Sub-kategori untuk Aksesoris
        Category::create([
            'name' => 'Charger & Kabel Data',
            'slug' => Str::slug('Charger & Kabel Data'),
            'parent_id' => $parentIds['aksesoris'] ?? null,
            'description' => 'Charger dan kabel data original dan berkualitas.',
        ]);
        Category::create([
            'name' => 'Casing & Pelindung Layar',
            'slug' => Str::slug('Casing & Pelindung Layar'),
            'parent_id' => $parentIds['aksesoris'] ?? null,
            'description' => 'Lindungi gadget Anda dengan casing dan pelindung layar.',
        ]);
        Category::create([
            'name' => 'Mouse & Keyboard',
            'slug' => Str::slug('Mouse & Keyboard'),
            'parent_id' => $parentIds['aksesoris'] ?? null,
            'description' => 'Mouse dan keyboard untuk laptop dan PC.',
        ]);
    }
}
