<?php
// File: simpan_mahasiswa.php
session_start(); // Wajib ada jika menggunakan $_SESSION
require_once 'koneksi_fkom.php'; // Koneksi ke DB 'PERPUSTAKAAN_FKOM'

// Variabel $conn dari koneksi_fkom.php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_mahasiswa'])) {
    // Ambil data dari form dan sanitasi dasar
    $nim_input      = isset($_POST['nim']) ? trim(mysqli_real_escape_string($conn, $_POST['nim'])) : null;
    $nama_input     = isset($_POST['nama']) ? trim(mysqli_real_escape_string($conn, $_POST['nama'])) : null;
    $fakultas_input = isset($_POST['fakultas']) ? trim(mysqli_real_escape_string($conn, $_POST['fakultas'])) : null;
    $jurusan_input  = isset($_POST['jurusan']) ? trim(mysqli_real_escape_string($conn, $_POST['jurusan'])) : null;
    $jenjang_input  = isset($_POST['jenjang']) ? trim(mysqli_real_escape_string($conn, $_POST['jenjang'])) : null;

    // NIM lama (hidden field) untuk mode update
    $nim_lama_hidden = isset($_POST['nim_lama']) ? trim(mysqli_real_escape_string($conn, $_POST['nim_lama'])) : null;

    // Validasi dasar: NIM dan Nama tidak boleh kosong
    if (empty($nim_input) || empty($nama_input)) {
        $_SESSION['message'] = "NIM dan Nama Mahasiswa tidak boleh kosong!";
        $_SESSION['message_type'] = "error";
        // Redirect kembali ke form yang sesuai
        if ($nim_lama_hidden) { // Jika ini dari mode edit
            header("Location: index.php?menu=input_mahasiswa&aksi=edit&nim=" . urlencode($nim_lama_hidden));
        } else { // Jika ini dari mode tambah baru
            header("Location: index.php?menu=input_mahasiswa");
        }
        exit;
    }

    if ($nim_lama_hidden) { // Mode UPDATE (jika nim_lama ada, berarti ini dari form edit)
        // Karena NIM di form diset readonly, $nim_input seharusnya sama dengan $nim_lama_hidden.
        // Query UPDATE menggunakan $nim_lama_hidden di WHERE clause.
        $stmt = mysqli_prepare($conn, "UPDATE mahasiswa SET NAMA = ?, FAKULTAS = ?, JURUSAN = ?, JENJANG = ? WHERE NIM = ?");
        mysqli_stmt_bind_param($stmt, "sssss", $nama_input, $fakultas_input, $jurusan_input, $jenjang_input, $nim_lama_hidden);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['message'] = "Data mahasiswa berhasil diupdate.";
                $_SESSION['message_type'] = "success";
            } else {
                // Tidak ada baris yang terpengaruh, bisa jadi tidak ada perubahan atau NIM tidak ditemukan
                $_SESSION['message'] = "Tidak ada perubahan pada data mahasiswa atau NIM tidak ditemukan.";
                $_SESSION['message_type'] = "info"; // Atau "warning"
            }
        } else {
            $_SESSION['message'] = "Gagal mengupdate data mahasiswa: " . mysqli_stmt_error($stmt);
            $_SESSION['message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    } else { // Mode INSERT (jika nim_lama tidak ada, ini dari form tambah baru)
        // Cek duplikasi NIM sebelum insert
        $cek_stmt = mysqli_prepare($conn, "SELECT NIM FROM mahasiswa WHERE NIM = ?");
        mysqli_stmt_bind_param($cek_stmt, "s", $nim_input);
        mysqli_stmt_execute($cek_stmt);
        mysqli_stmt_store_result($cek_stmt);

        if (mysqli_stmt_num_rows($cek_stmt) > 0) {
            $_SESSION['message'] = "Gagal menambahkan: NIM '" . htmlspecialchars($nim_input) . "' sudah ada di database.";
            $_SESSION['message_type'] = "error";
            mysqli_stmt_close($cek_stmt);
            header("Location: index.php?menu=input_mahasiswa"); // Kembali ke form input
            exit;
        }
        mysqli_stmt_close($cek_stmt);

        // Lanjutkan dengan proses INSERT
        $stmt = mysqli_prepare($conn, "INSERT INTO mahasiswa (NIM, NAMA, FAKULTAS, JURUSAN, JENJANG) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nim_input, $nama_input, $fakultas_input, $jurusan_input, $jenjang_input);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Data mahasiswa baru berhasil ditambahkan.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menambahkan data mahasiswa: " . mysqli_stmt_error($stmt);
            $_SESSION['message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    }
    // Setelah operasi, redirect kembali ke halaman daftar mahasiswa
    header("Location: index.php?menu=tampil_mahasiswa");
    exit;
} else {
    // Jika diakses langsung tanpa metode POST atau tanpa submit_mahasiswa, redirect
    $_SESSION['message'] = "Akses tidak sah ke skrip simpan data mahasiswa.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php?menu=tampil_mahasiswa");
    exit;
}
