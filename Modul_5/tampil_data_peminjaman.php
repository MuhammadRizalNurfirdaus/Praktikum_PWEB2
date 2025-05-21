<?php
// File: tampil_data_peminjaman.php
require_once 'koneksi_fkom.php'; // Koneksi ke DB 'PERPUSTAKAAN_FKOM'

// Variabel $conn dari koneksi_fkom.php

$r_peminjaman = null;    // Untuk hasil query daftar semua peminjaman
$data_peminjaman = null; // Untuk data satu peminjaman (array) saat mode edit

// Ambil parameter dari URL
$current_menu_pinjam = isset($_GET['menu']) ? $_GET['menu'] : null;
$current_kode_pinjam = isset($_GET['kode_pinjam']) ? $_GET['kode_pinjam'] : null;
$current_aksi_pinjam = isset($_GET['aksi']) ? $_GET['aksi'] : null;

if (isset($conn) && $conn) { // Pastikan koneksi ada
    if ($current_menu_pinjam == "input_peminjaman" && $current_aksi_pinjam == "edit" && $current_kode_pinjam !== null) {
        // Mode Edit: Ambil data peminjaman spesifik
        $stmt = mysqli_prepare($conn, "SELECT KODE_PINJAM, TANGGAL_PINJAM, NIM, KODE_BUKU, TANGGAL_KEMBALI FROM peminjaman WHERE KODE_PINJAM = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $current_kode_pinjam);
            mysqli_stmt_execute($stmt);
            $result_edit = mysqli_stmt_get_result($stmt);
            if ($result_edit) {
                $data_peminjaman = mysqli_fetch_assoc($result_edit);
                if (!$data_peminjaman) {
                    $_SESSION['message'] = "Data peminjaman dengan Kode Pinjam '$current_kode_pinjam' tidak ditemukan.";
                    $_SESSION['message_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "Gagal mengambil data peminjaman untuk diedit: " . mysqli_error($conn);
                $_SESSION['message_type'] = "error";
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Terjadi kesalahan internal saat mempersiapkan data edit peminjaman: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    } elseif ($current_menu_pinjam == "tampil_peminjaman") {
        // Mode Tampil Semua: Ambil semua data peminjaman dengan join untuk nama mahasiswa dan judul buku
        $query_select_all = "SELECT p.KODE_PINJAM, p.TANGGAL_PINJAM, p.NIM, m.NAMA AS NAMA_MAHASISWA, p.KODE_BUKU, b.JUDUL AS JUDUL_BUKU, p.TANGGAL_KEMBALI 
                             FROM peminjaman p
                             LEFT JOIN mahasiswa m ON p.NIM = m.NIM
                             LEFT JOIN buku b ON p.KODE_BUKU = b.KD_BUKU 
                             ORDER BY p.TANGGAL_PINJAM DESC, p.KODE_PINJAM ASC";
        // Asumsi tabel buku ada di DB yang sama (PERPUSTAKAAN_FKOM) atau Anda perlu cross-database query jika beda DB & server mengizinkan
        // Jika tabel buku di DB 'perpustakaan', query JOIN akan lebih kompleks atau perlu pendekatan berbeda
        $r_peminjaman = mysqli_query($conn, $query_select_all);
        if (!$r_peminjaman) {
            $_SESSION['message'] = "Gagal mengambil daftar peminjaman: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    }
} elseif (!isset($conn) || !$conn) {
    if ($current_menu_pinjam == "input_peminjaman" || $current_menu_pinjam == "tampil_peminjaman") {
        $_SESSION['message'] = "Koneksi ke database PERPUSTAKAAN_FKOM tidak tersedia untuk modul peminjaman.";
        $_SESSION['message_type'] = "error";
    }
}
