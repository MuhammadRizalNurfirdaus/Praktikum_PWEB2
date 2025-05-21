<?php
// File: index.php
session_start(); // Mulai session di paling atas

// PERIKSA APAKAH PENGGUNA SUDAH LOGIN
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    // Jika belum login, redirect ke halaman login
    $_SESSION['login_error'] = "Anda harus login untuk mengakses halaman ini.";
    header("Location: login.php");
    exit;
}

// Aktifkan pelaporan error selama pengembangan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Include file koneksi database
require_once "koneksi.php"; // $conn akan tersedia

// 2. Ambil parameter dari URL
$menu = isset($_GET['menu']) ? $_GET['menu'] : null;
$id   = isset($_GET['id'])   ? $_GET['id']   : null; // Untuk edit/hapus buku
$nim  = isset($_GET['nim'])  ? $_GET['nim']  : null; // Untuk edit/hapus mahasiswa
$kode_pinjam = isset($_GET['kode_pinjam']) ? $_GET['kode_pinjam'] : null; // Untuk edit/hapus peminjaman
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : null;

// 3. Include file yang menyiapkan data berdasarkan menu
// Ini akan menyiapkan variabel seperti $r (untuk list) atau $data (untuk form edit)
if ($menu === 'tampil_buku' || ($menu === 'input_buku' && $aksi === 'edit') || $menu === null) {
    require_once "tampil_data_buku.php"; // Asumsi sudah ada dan menyiapkan $r atau $data
} elseif ($menu === 'tampil_mahasiswa' || ($menu === 'input_mahasiswa' && $aksi === 'edit')) {
    require_once "tampil_data_mahasiswa.php"; // Menyiapkan $r_mahasiswa atau $data_mahasiswa
} elseif ($menu === 'tampil_peminjaman' || ($menu === 'input_peminjaman' && $aksi === 'edit')) {
    require_once "tampil_data_peminjaman.php"; // Menyiapkan $r_peminjaman atau $data_peminjaman
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="style.css"> <!-- Kita akan buat file style.css -->
</head>

<body>
    <div class="container">
        <header class="main-header">
            <h1>PERPUSTAKAAN</h1>
            <div class="user-info">
                Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_nama']); ?>!
                (<a href="logout.php">Logout</a>)
            </div>
        </header>
        <nav class="main-nav">
            <a href="index.php?menu=tampil_buku">Data Buku</a>
            <a href="index.php?menu=tampil_mahasiswa">Data Mahasiswa</a>
            <a href="index.php?menu=tampil_peminjaman">Data Peminjaman</a>
            <!-- Tambahkan link menu lain jika ada -->
        </nav>

        <main class="content">
            <?php
            // Tampilkan pesan notifikasi dari session jika ada
            if (isset($_SESSION['message'])) {
                echo '<div class="message ' . ($_SESSION['message_type'] ?? 'info') . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }

            // 4. Logika routing untuk menampilkan konten utama
            // Modul Buku (diasumsikan sudah ada)
            if ($menu === null || $menu == "tampil_buku") {
                require "tampil_buku.php"; // $r sudah disiapkan
            } elseif ($menu == "input_buku") {
                require "input_buku.php"; // $data (jika edit) sudah disiapkan
            } elseif ($menu == "simpan_buku") {
                require "simpan_buku.php";
            } elseif ($menu == "hapus_buku" && $aksi == "delete" && $id !== null) {
                require "hapus_buku.php";
            }
            // Modul Mahasiswa
            elseif ($menu == "tampil_mahasiswa") {
                require "tampil_mahasiswa.php"; // $r_mahasiswa sudah disiapkan
            } elseif ($menu == "input_mahasiswa") {
                require "input_mahasiswa.php"; // $data_mahasiswa (jika edit) sudah disiapkan
            } elseif ($menu == "simpan_mahasiswa") {
                require "simpan_mahasiswa.php";
            } elseif ($menu == "hapus_mahasiswa" && $aksi == "delete" && $nim !== null) {
                require "hapus_mahasiswa.php";
            }
            // Modul Peminjaman
            elseif ($menu == "tampil_peminjaman") {
                require "tampil_peminjaman.php"; // $r_peminjaman sudah disiapkan
            } elseif ($menu == "input_peminjaman") {
                require "input_peminjaman.php"; // $data_peminjaman (jika edit) sudah disiapkan
            } elseif ($menu == "simpan_peminjaman") {
                require "simpan_peminjaman.php";
            } elseif ($menu == "hapus_peminjaman" && $aksi == "delete" && $kode_pinjam !== null) {
                require "hapus_peminjaman.php";
            }
            // Tambahkan elseif untuk menu lain
            else {
                // Jika menu tidak dikenal, tampilkan halaman default atau pesan error
                // echo "<p>Halaman tidak ditemukan atau menu belum diimplementasikan.</p>";
                // require "tampil_buku.php"; // Kembali ke halaman utama (buku)
            }
            ?>
        </main>
        <footer class="main-footer">
            <p>© <?php echo date("Y"); ?> Perpustakaan FKOM. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>