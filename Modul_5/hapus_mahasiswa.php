<?php
// File: hapus_mahasiswa.php
session_start(); // Wajib ada jika menggunakan $_SESSION
require_once 'koneksi_fkom.php'; // Koneksi ke DB 'PERPUSTAKAAN_FKOM'

// Variabel $conn dari koneksi_fkom.php
// Parameter 'nim' diambil dari URL (diteruskan oleh index.php)

if (isset($_GET['nim']) && isset($conn) && $conn) {
    $nim_untuk_dihapus = mysqli_real_escape_string($conn, $_GET['nim']);

    // Siapkan statement DELETE
    $stmt = mysqli_prepare($conn, "DELETE FROM mahasiswa WHERE NIM = ?");
    mysqli_stmt_bind_param($stmt, "s", $nim_untuk_dihapus);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['message'] = "Data mahasiswa dengan NIM '" . htmlspecialchars($nim_untuk_dihapus) . "' berhasil dihapus.";
            $_SESSION['message_type'] = "success";
        } else {
            // Tidak ada baris yang terpengaruh, kemungkinan NIM tidak ditemukan
            $_SESSION['message'] = "Gagal menghapus: Data mahasiswa dengan NIM '" . htmlspecialchars($nim_untuk_dihapus) . "' tidak ditemukan.";
            $_SESSION['message_type'] = "error"; // Atau "info"
        }
    } else {
        // Error saat eksekusi statement
        $_SESSION['message'] = "Gagal menghapus data mahasiswa: " . mysqli_stmt_error($stmt);
        $_SESSION['message_type'] = "error";
    }
    mysqli_stmt_close($stmt);
} else {
    // Jika parameter 'nim' tidak ada atau koneksi gagal
    $_SESSION['message'] = "Permintaan hapus data mahasiswa tidak valid atau koneksi database gagal.";
    $_SESSION['message_type'] = "error";
}

// Redirect kembali ke halaman daftar mahasiswa setelah operasi
header("Location: index.php?menu=tampil_mahasiswa");
exit;
