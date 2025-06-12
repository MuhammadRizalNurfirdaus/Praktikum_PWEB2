<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all(); // Ambil semua pengguna

        if ($users->isEmpty()) {
            $this->command->info('Tidak ada pengguna untuk ditambahkan alamat. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        foreach ($users as $user) {
            // Tambahkan 1-2 alamat untuk setiap pengguna
            Address::create([
                'user_id' => $user->id,
                'label' => 'Rumah',
                'recipient_name' => $user->name,
                'phone_number' => $user->phone_number ?? '081200001111', // Ambil dari user atau default
                'address_line1' => 'Jl. Kebahagiaan No. ' . rand(1, 100),
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12' . rand(100, 999),
                'is_primary_shipping' => true, // Alamat pertama jadi primary shipping
                'is_primary_billing' => true,  // dan primary billing
            ]);

            // Alamat kedua (opsional)
            if (rand(0, 1)) { // 50% chance untuk punya alamat kedua
                Address::create([
                    'user_id' => $user->id,
                    'label' => 'Kantor',
                    'recipient_name' => $user->name,
                    'phone_number' => $user->phone_number ?? '081200002222',
                    'address_line1' => 'Gedung Cyber Lt. ' . rand(1, 10) . ', Jl. Teknologi Raya No. 1',
                    'city' => 'Jakarta Pusat',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '10' . rand(100, 999),
                ]);
            }
            $this->command->info("Menambahkan alamat untuk pengguna: " . $user->name);
        }
    }
}
