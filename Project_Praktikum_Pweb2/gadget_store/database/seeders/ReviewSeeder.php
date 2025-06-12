<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'customer')->take(2)->get(); // Ambil 2 customer
        $products = Product::take(5)->get(); // Ambil 5 produk

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Pengguna atau produk tidak cukup untuk membuat review. Jalankan UserSeeder dan ProductSeeder.');
            return;
        }

        $comments = [
            "Produknya bagus banget, sesuai ekspektasi!",
            "Pengiriman cepat, packing aman. Recommended seller.",
            "Kualitasnya oke untuk harga segini.",
            "Fiturnya lengkap, sangat membantu pekerjaan saya.",
            "Agak sedikit kecewa dengan daya tahan baterai, tapi overall oke.",
            "Desainnya elegan, suka banget!",
        ];

        foreach ($products as $product) {
            foreach ($users as $user) {
                // Beri kesempatan untuk tidak semua user mereview semua produk
                if (rand(0, 1)) { // 50% chance user ini mereview produk ini
                    // Cek apakah user sudah mereview produk ini sebelumnya (jika ada unique constraint)
                    $existingReview = Review::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();
                    if (!$existingReview) {
                        Review::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'rating' => rand(3, 5), // Rating antara 3 sampai 5
                            'comment' => $comments[array_rand($comments)],
                            'is_approved' => true,
                        ]);
                        $this->command->info("Pengguna {$user->name} mereview produk: {$product->name}");
                    }
                }
            }
        }
    }
}
