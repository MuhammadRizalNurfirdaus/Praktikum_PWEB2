<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- Import model User
use Illuminate\Support\Facades\Hash; // <-- Import Hash untuk password

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat User Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gadgetstore.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'phone_number' => '081234567890',
            'role' => 'admin',
        ]);

        // Membuat User Customer
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@gadgetstore.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'phone_number' => '089876543210',
            'role' => 'customer',
        ]);

        // Anda bisa menggunakan factory untuk membuat lebih banyak user dummy jika perlu
        // User::factory(10)->create(); // Ini membutuhkan file UserFactory.php yang sudah di-setup
    }
}
