<?php
// File: Koneksi_FKOM.php

$host_fkom     = "localhost";
$user_fkom     = "root"; // Sesuaikan jika username database Anda berbeda
$pass_fkom     = "";     // Sesuaikan jika XAMPP Anda memiliki password root
$db_name_fkom  = "perpustakaan_fkom"; // Nama database sesuai soal

// Membuat koneksi
$conn_fkom = mysqli_connect($host_fkom, $user_fkom, $pass_fkom, $db_name_fkom);

// Memeriksa koneksi
if (!$conn_fkom) {
    // Hentikan eksekusi dan tampilkan pesan error jika koneksi gagal
    // htmlspecialchars() digunakan untuk mencegah XSS jika pesan error ditampilkan di browser
    die("Koneksi ke database PERPUSTAKAAN_FKOM gagal: " . htmlspecialchars(mysqli_connect_error()) .
        "<br>Pastikan server MySQL berjalan dan detail koneksi benar. Database yang dicari: '" . htmlspecialchars($db_name_fkom) . "'");
}

// (Opsional, tapi direkomendasikan) Atur character set ke utf8mb4 untuk dukungan karakter yang lebih baik
if (!mysqli_set_charset($conn_fkom, "utf8mb4")) {
    // Anda bisa memilih untuk menampilkan warning atau mengabaikannya jika gagal,
    // tapi ini adalah praktik yang baik untuk konsistensi encoding.
    // printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn_fkom));
}

// Variabel $conn_fkom sekarang siap digunakan untuk query ke database perpustakaan_fkom
