<?php
// File: tampil_data_buku.php
// Lokasi: Pemrograman_Web_2/Praktikum/Modul_5/tampil_data_buku.php
// File ini bertugas menyiapkan data yang akan digunakan oleh file view.
// Variabel $conn sudah tersedia dari index.php (yang meng-include koneksi.php).

// Inisialisasi variabel yang akan digunakan global oleh file view
$r = null;    // Untuk hasil query daftar semua buku (mysqli_result object)
$data = null; // Untuk data satu buku (array asosiatif) saat mode edit

// Ambil parameter dari URL (sudah diambil di index.php, tapi bisa diambil lagi di sini jika file ini dipanggil terpisah)
$current_menu = isset($_GET['menu']) ? $_GET['menu'] : null;
$current_id   = isset($_GET['id'])   ? $_GET['id']   : null;
$current_aksi = isset($_GET['aksi']) ? $_GET['aksi'] : null;

// Pastikan koneksi tersedia sebelum melakukan query
if (isset($conn) && $conn) {
    if ($current_menu == "input_buku" && $current_aksi == "edit" && $current_id !== null) {
        // Mode Edit: Ambil data buku spesifik untuk ditampilkan di form input
        $stmt = mysqli_prepare($conn, "SELECT KD_BUKU, JUDUL, PENGARANG, PENERBIT FROM buku WHERE KD_BUKU = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $current_id); // Asumsi KD_BUKU adalah string, jika integer ganti "s" dengan "i"
            mysqli_stmt_execute($stmt);
            $result_for_edit = mysqli_stmt_get_result($stmt);
            if ($result_for_edit) {
                $data = mysqli_fetch_assoc($result_for_edit); // $data akan digunakan di input_buku.php
            } else {
                // echo "<!-- DEBUG: tampil_data_buku.php - Gagal mendapatkan hasil untuk edit: " . mysqli_error($conn) . " -->\n";
                $_SESSION['message'] = "Gagal mengambil data buku untuk diedit: " . mysqli_error($conn);
                $_SESSION['message_type'] = "error";
            }
            mysqli_stmt_close($stmt);
        } else {
            // echo "<!-- DEBUG: tampil_data_buku.php - Gagal mempersiapkan statement untuk edit: " . mysqli_error($conn) . " -->\n";
            $_SESSION['message'] = "Terjadi kesalahan saat mempersiapkan data edit: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    } elseif ($current_menu === null || $current_menu == "tampil_buku") {
        // Mode Tampil Semua: Ambil semua data buku
        $query_select_all = "SELECT KD_BUKU, JUDUL, PENGARANG, PENERBIT FROM buku ORDER BY KD_BUKU ASC";
        $r = mysqli_query($conn, $query_select_all); // $r akan digunakan di tampil_buku.php

        if (!$r) {
            // echo "<!-- DEBUG: tampil_data_buku.php - KESALAHAN QUERY SELECT ALL: " . htmlspecialchars(mysqli_error($conn)) . " -->\n";
            $_SESSION['message'] = "Gagal mengambil daftar buku: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
            $r = null; // Pastikan $r null jika query gagal
        }
    }
    // Bisa ditambahkan kondisi lain jika ada menu lain yang butuh data dari database
} else {
    // Jika tidak ada koneksi, set pesan error
    // echo "<!-- DEBUG: tampil_data_buku.php - KESALAHAN FATAL! Koneksi database (\$conn) tidak tersedia. -->\n";
    $_SESSION['message'] = "Koneksi database tidak tersedia. Tidak dapat mengambil data.";
    $_SESSION['message_type'] = "error";
    // Pastikan $r dan $data tetap null
    $r = null;
    $data = null;
}
