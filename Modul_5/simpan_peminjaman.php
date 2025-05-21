<?php
// File: simpan_peminjaman.php
session_start();
require_once 'koneksi_fkom.php'; // Koneksi ke DB 'PERPUSTAKAAN_FKOM'

// Variabel $conn dari koneksi_fkom.php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_peminjaman'])) {
    // Ambil data dari form
    $kode_pinjam_input     = isset($_POST['kode_pinjam']) ? trim(mysqli_real_escape_string($conn, $_POST['kode_pinjam'])) : null;
    $tanggal_pinjam_input  = isset($_POST['tanggal_pinjam']) ? trim(mysqli_real_escape_string($conn, $_POST['tanggal_pinjam'])) : null;
    $nim_input             = isset($_POST['nim']) ? trim(mysqli_real_escape_string($conn, $_POST['nim'])) : null;
    $kode_buku_input       = isset($_POST['kode_buku']) ? trim(mysqli_real_escape_string($conn, $_POST['kode_buku'])) : null;
    // Tanggal kembali bisa NULL jika dikosongkan
    $tanggal_kembali_input = isset($_POST['tanggal_kembali']) && !empty($_POST['tanggal_kembali']) ? trim(mysqli_real_escape_string($conn, $_POST['tanggal_kembali'])) : null;

    $kode_pinjam_lama_hidden = isset($_POST['kode_pinjam_lama']) ? trim(mysqli_real_escape_string($conn, $_POST['kode_pinjam_lama'])) : null;

    // Validasi dasar
    if (empty($kode_pinjam_input) || empty($tanggal_pinjam_input) || empty($nim_input) || empty($kode_buku_input)) {
        $_SESSION['message'] = "Kode Pinjam, Tanggal Pinjam, NIM, dan Kode Buku tidak boleh kosong!";
        $_SESSION['message_type'] = "error";
        if ($kode_pinjam_lama_hidden) {
            header("Location: index.php?menu=input_peminjaman&aksi=edit&kode_pinjam=" . urlencode($kode_pinjam_lama_hidden));
        } else {
            header("Location: index.php?menu=input_peminjaman");
        }
        exit;
    }

    // Validasi tambahan: Cek apakah NIM ada di tabel mahasiswa
    $stmt_cek_nim = mysqli_prepare($conn, "SELECT NIM FROM mahasiswa WHERE NIM = ?");
    mysqli_stmt_bind_param($stmt_cek_nim, "s", $nim_input);
    mysqli_stmt_execute($stmt_cek_nim);
    mysqli_stmt_store_result($stmt_cek_nim);
    if (mysqli_stmt_num_rows($stmt_cek_nim) == 0) {
        $_SESSION['message'] = "NIM Peminjam '" . htmlspecialchars($nim_input) . "' tidak ditemukan di data mahasiswa.";
        $_SESSION['message_type'] = "error";
        mysqli_stmt_close($stmt_cek_nim);
        header("Location: index.php?menu=input_peminjaman" . ($kode_pinjam_lama_hidden ? "&aksi=edit&kode_pinjam=" . urlencode($kode_pinjam_lama_hidden) : ""));
        exit;
    }
    mysqli_stmt_close($stmt_cek_nim);

    // Validasi tambahan: Cek apakah KODE_BUKU ada di tabel buku (di DB yang sama: PERPUSTAKAAN_FKOM)
    // Jika tabel buku di DB lain, validasi ini lebih rumit atau dihilangkan.
    $stmt_cek_buku = mysqli_prepare($conn, "SELECT KD_BUKU FROM buku WHERE KD_BUKU = ?");
    mysqli_stmt_bind_param($stmt_cek_buku, "s", $kode_buku_input);
    mysqli_stmt_execute($stmt_cek_buku);
    mysqli_stmt_store_result($stmt_cek_buku);
    if (mysqli_stmt_num_rows($stmt_cek_buku) == 0) {
        $_SESSION['message'] = "Kode Buku '" . htmlspecialchars($kode_buku_input) . "' tidak ditemukan di katalog buku.";
        $_SESSION['message_type'] = "error";
        mysqli_stmt_close($stmt_cek_buku);
        header("Location: index.php?menu=input_peminjaman" . ($kode_pinjam_lama_hidden ? "&aksi=edit&kode_pinjam=" . urlencode($kode_pinjam_lama_hidden) : ""));
        exit;
    }
    mysqli_stmt_close($stmt_cek_buku);


    if ($kode_pinjam_lama_hidden) { // Mode UPDATE
        // Kode Pinjam (PK) tidak diubah, jadi $kode_pinjam_input seharusnya sama dengan $kode_pinjam_lama_hidden.
        // Update dilakukan berdasarkan $kode_pinjam_lama_hidden.
        $stmt = mysqli_prepare($conn, "UPDATE peminjaman SET TANGGAL_PINJAM = ?, NIM = ?, KODE_BUKU = ?, TANGGAL_KEMBALI = ? WHERE KODE_PINJAM = ?");
        mysqli_stmt_bind_param($stmt, "sssss", $tanggal_pinjam_input, $nim_input, $kode_buku_input, $tanggal_kembali_input, $kode_pinjam_lama_hidden);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['message'] = "Data peminjaman berhasil diupdate.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Tidak ada perubahan pada data peminjaman atau Kode Pinjam tidak ditemukan.";
                $_SESSION['message_type'] = "info";
            }
        } else {
            $_SESSION['message'] = "Gagal mengupdate data peminjaman: " . mysqli_stmt_error($stmt);
            $_SESSION['message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    } else { // Mode INSERT
        // Cek duplikasi Kode Pinjam sebelum insert
        $cek_stmt = mysqli_prepare($conn, "SELECT KODE_PINJAM FROM peminjaman WHERE KODE_PINJAM = ?");
        mysqli_stmt_bind_param($cek_stmt, "s", $kode_pinjam_input);
        mysqli_stmt_execute($cek_stmt);
        mysqli_stmt_store_result($cek_stmt);

        if (mysqli_stmt_num_rows($cek_stmt) > 0) {
            $_SESSION['message'] = "Gagal menambahkan: Kode Pinjam '" . htmlspecialchars($kode_pinjam_input) . "' sudah ada.";
            $_SESSION['message_type'] = "error";
            mysqli_stmt_close($cek_stmt);
            header("Location: index.php?menu=input_peminjaman");
            exit;
        }
        mysqli_stmt_close($cek_stmt);

        // Lanjutkan INSERT
        $stmt = mysqli_prepare($conn, "INSERT INTO peminjaman (KODE_PINJAM, TANGGAL_PINJAM, NIM, KODE_BUKU, TANGGAL_KEMBALI) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $kode_pinjam_input, $tanggal_pinjam_input, $nim_input, $kode_buku_input, $tanggal_kembali_input);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Data peminjaman baru berhasil ditambahkan.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menambahkan data peminjaman: " . mysqli_stmt_error($stmt);
            $_SESSION['message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    }
    header("Location: index.php?menu=tampil_peminjaman");
    exit;
} else {
    $_SESSION['message'] = "Akses tidak sah ke skrip simpan data peminjaman.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php?menu=tampil_peminjaman");
    exit;
}
