<?php
// File: tampil_data_mahasiswa.php
require_once 'koneksi_fkom.php'; // Koneksi ke DB 'PERPUSTAKAAN_FKOM'

// Variabel $conn dari koneksi_fkom.php

$r_mahasiswa = null;    // Untuk hasil query daftar semua mahasiswa
$data_mahasiswa = null; // Untuk data satu mahasiswa (array) saat mode edit

// Ambil parameter menu dan aksi dari URL (diteruskan dari index.php)
$current_menu_mhs = isset($_GET['menu']) ? $_GET['menu'] : null;
$current_nim_mhs  = isset($_GET['nim'])  ? $_GET['nim']  : null;
$current_aksi_mhs = isset($_GET['aksi']) ? $_GET['aksi'] : null;

if (isset($conn) && $conn) { // Pastikan koneksi ada
    if ($current_menu_mhs == "input_mahasiswa" && $current_aksi_mhs == "edit" && $current_nim_mhs !== null) {
        // Mode Edit: Ambil data mahasiswa spesifik
        $stmt = mysqli_prepare($conn, "SELECT NIM, NAMA, FAKULTAS, JURUSAN, JENJANG FROM mahasiswa WHERE NIM = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $current_nim_mhs);
            mysqli_stmt_execute($stmt);
            $result_edit = mysqli_stmt_get_result($stmt);
            if ($result_edit) {
                $data_mahasiswa = mysqli_fetch_assoc($result_edit);
                if (!$data_mahasiswa) { // Jika NIM tidak ditemukan
                    $_SESSION['message'] = "Data mahasiswa dengan NIM '$current_nim_mhs' tidak ditemukan.";
                    $_SESSION['message_type'] = "error";
                }
            } else {
                // Gagal mendapatkan hasil dari statement
                $_SESSION['message'] = "Gagal mengambil data mahasiswa untuk diedit: " . mysqli_error($conn);
                $_SESSION['message_type'] = "error";
            }
            mysqli_stmt_close($stmt);
        } else {
            // Gagal mempersiapkan statement
            $_SESSION['message'] = "Terjadi kesalahan internal saat mempersiapkan data edit mahasiswa: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
        }
    } elseif ($current_menu_mhs == "tampil_mahasiswa") {
        // Mode Tampil Semua: Ambil semua data mahasiswa
        // Atau jika menu adalah null dan ini adalah default untuk mahasiswa (tergantung index.php)
        $query_select_all = "SELECT NIM, NAMA, FAKULTAS, JURUSAN, JENJANG FROM mahasiswa ORDER BY NAMA ASC";
        $r_mahasiswa = mysqli_query($conn, $query_select_all);
        if (!$r_mahasiswa) {
            $_SESSION['message'] = "Gagal mengambil daftar mahasiswa: " . mysqli_error($conn);
            $_SESSION['message_type'] = "error";
            // $r_mahasiswa akan false, yang akan ditangani di tampil_mahasiswa.php
        }
    }
    // Bisa ditambahkan kondisi lain jika ada menu lain yang butuh data mahasiswa
} elseif (!isset($conn) || !$conn) {
    // Hanya set error jika menu terkait mahasiswa dan koneksi tidak ada
    // Ini untuk jaga-jaga jika file dipanggil dalam konteks yang salah
    if ($current_menu_mhs == "input_mahasiswa" || $current_menu_mhs == "tampil_mahasiswa") {
        $_SESSION['message'] = "Koneksi ke database PERPUSTAKAAN_FKOM tidak tersedia untuk modul mahasiswa.";
        $_SESSION['message_type'] = "error";
    }
}
