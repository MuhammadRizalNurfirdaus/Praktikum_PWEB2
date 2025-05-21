<?php
// File: koneksi.php
$host = "localhost";
$user = "root";
$pass = ""; // Sesuaikan jika XAMPP Anda memiliki password root
$db_name = "perpustakaan"; // Database yang berisi tabel admin, buku, mahasiswa, peminjaman

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error() .
        "<br>Pastikan server MySQL berjalan dan detail koneksi benar. Database yang dicari: '" . htmlspecialchars($db_name) . "'");
}
// mysqli_set_charset($conn, "utf8mb4"); // Opsional, untuk karakter set
