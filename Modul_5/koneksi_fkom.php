<?php
// File: koneksi_fkom.php
$host_fkom = "localhost";
$user_fkom = "root";
$pass_fkom = ""; // Sesuaikan jika XAMPP Anda memiliki password root
$db_name_fkom = "PERPUSTAKAAN_FKOM"; // Database untuk mahasiswa dan peminjaman

$conn = mysqli_connect($host_fkom, $user_fkom, $pass_fkom, $db_name_fkom);

if (!$conn) {
    // Tampilkan error dengan detail database yang gagal dikoneksikan
    die("Koneksi ke database '" . htmlspecialchars($db_name_fkom) . "' GAGAL: " . mysqli_connect_error() .
        "<br>Pastikan server MySQL berjalan dan detail koneksi benar.");
}
// mysqli_set_charset($conn, "utf8mb4"); // Opsional
