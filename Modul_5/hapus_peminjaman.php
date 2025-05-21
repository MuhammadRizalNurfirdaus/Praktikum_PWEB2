<?php
// File: hapus_peminjaman.php
session_start();
require_once 'koneksi_fkom.php'; // Koneksi ke DB 'PERPUSTAKAAN_FKOM'

// Variabel $conn dari koneksi_fkom.php
// Parameter 'kode_pinjam' dari URL

if (isset($_GET['kode_pinjam']) && isset($conn) && $conn) {
    $kode_pinjam_untuk_dihapus = mysqli_real_escape_string($conn, $_GET['kode_pinjam']);

    $stmt = mysqli_prepare($conn, "DELETE FROM peminjaman WHERE KODE_PINJAM = ?");
    mysqli_stmt_bind_param($stmt, "s", $kode_pinjam_untuk_dihapus);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['message'] = "Data peminjaman dengan Kode Pinjam '" . htmlspecialchars($kode_pinjam_untuk_dihapus) . "' berhasil dihapus.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menghapus: Data peminjaman dengan Kode Pinjam '" . htmlspecialchars($kode_pinjam_untuk_dihapus) . "' tidak ditemukan.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Gagal menghapus data peminjaman: " . mysqli_stmt_error($stmt);
        $_SESSION['message_type'] = "error";
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = "Permintaan hapus data peminjaman tidak valid atau koneksi database gagal.";
    $_SESSION['message_type'] = "error";
}
header("Location: index.php?menu=tampil_peminjaman");
exit;
