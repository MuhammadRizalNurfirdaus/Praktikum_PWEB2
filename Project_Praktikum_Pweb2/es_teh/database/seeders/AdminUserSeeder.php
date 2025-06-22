<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Bisa dihapus jika tidak menggunakan event
use Illuminate\Database\Seeder;
use App\Models\User; // Import model User Anda
use Illuminate\Support\Facades\Hash; // Import Facade Hash untuk mengenkripsi password

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Membuat atau Mengupdate Akun Admin Utama
        User::updateOrCreate(
            [
                'email' => 'admin@example.com', // Kunci pencarian, pastikan email ini unik untuk admin
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin12345'), // GANTI DENGAN PASSWORD YANG KUAT DAN AMAN!
                'email_verified_at' => now(), // Langsung set email terverifikasi
                'role' => 'admin', // Set peran sebagai 'admin'
                // Anda bisa menambahkan field lain dari tabel users jika ada yang perlu diisi default
            ]
        );

        // Membuat atau Mengupdate Akun Kurir Contoh
        User::updateOrCreate(
            [
                'email' => 'kurir.utama@esteh.com', // Kunci pencarian, pastikan email ini unik untuk kurir
            ],
            [
                'name' => 'Kurir Utama Cepat',
                'password' => Hash::make('kurir12345'), // GANTI DENGAN PASSWORD YANG KUAT DAN AMAN!
                'email_verified_at' => now(), // Langsung set email terverifikasi
                'role' => 'kurir', // Set peran sebagai 'kurir'
            ]
        );

        // Anda bisa menambahkan user lain dengan peran berbeda jika diperlukan
        // Contoh User Biasa:
        // User::updateOrCreate(
        //     [
        //         'email' => 'userbiasa@example.com',
        //     ],
        //     [
        //         'name' => 'Pelanggan Biasa',
        //         'password' => Hash::make('user123'),
        //         'email_verified_at' => now(),
        //         'role' => 'user',
        //     ]
        // );

        // Anda bisa menggunakan factory juga jika ingin membuat banyak data dummy user
        // \App\Models\User::factory(5)->create(['role' => 'user']); // Membuat 5 user biasa
    }
}
