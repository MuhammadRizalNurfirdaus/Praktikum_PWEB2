<?php
// File: simpan_buku.php
// Lokasi: Pemrograman_Web_2/Praktikum/Modul_5/simpan_buku.php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_action'])) {

    $kode_buku_baru = isset($_POST['kode_buku']) ? trim(mysqli_real_escape_string($conn, $_POST['kode_buku'])) : null;
    $judul_buku     = isset($_POST['judul']) ? trim(mysqli_real_escape_string($conn, $_POST['judul'])) : null;
    $pengarang_buku = isset($_POST['pengarang']) ? trim(mysqli_real_escape_string($conn, $_POST['pengarang'])) : null;
    $penerbit_buku  = isset($_POST['penerbit']) ? trim(mysqli_real_escape_string($conn, $_POST['penerbit'])) : null;
    $submit_action  = $_POST['submit_action'];

    $id_buku_lama = isset($_POST['id_buku_lama']) ? trim(mysqli_real_escape_string($conn, $_POST['id_buku_lama'])) : null;

    // Validasi dasar
    if (empty($kode_buku_baru) || empty($judul_buku)) {
        $_SESSION['message'] = "Kode Buku dan Judul Buku tidak boleh kosong!";
        $_SESSION['message_type'] = "error";
        if ($submit_action == "Update Data" && $id_buku_lama) {
            header("Location: index.php?menu=input_buku&aksi=edit&id=" . urlencode($id_buku_lama));
        } else {
            header("Location: index.php?menu=input_buku");
        }
        exit;
    }

    if ($submit_action == "Simpan Data") { // Mode INSERT (tidak berubah dari sebelumnya)
        $cek_stmt = mysqli_prepare($conn, "SELECT KD_BUKU FROM buku WHERE KD_BUKU = ?");
        mysqli_stmt_bind_param($cek_stmt, "s", $kode_buku_baru);
        mysqli_stmt_execute($cek_stmt);
        mysqli_stmt_store_result($cek_stmt);

        if (mysqli_stmt_num_rows($cek_stmt) > 0) {
            $_SESSION['message'] = "Gagal menambahkan: Kode Buku '$kode_buku_baru' sudah ada.";
            $_SESSION['message_type'] = "error";
            mysqli_stmt_close($cek_stmt);
            header("Location: index.php?menu=input_buku");
            exit;
        }
        mysqli_stmt_close($cek_stmt);

        $stmt = mysqli_prepare($conn, "INSERT INTO buku (KD_BUKU, JUDUL, PENGARANG, PENERBIT) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $kode_buku_baru, $judul_buku, $pengarang_buku, $penerbit_buku);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Data buku baru berhasil ditambahkan.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menambahkan data buku: " . mysqli_stmt_error($stmt);
            $_SESSION['message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    } elseif ($submit_action == "Update Data" && $id_buku_lama) { // Mode UPDATE

        // Cek jika Kode Buku diubah, apakah Kode Buku yang BARU sudah ada untuk buku LAIN
        if ($kode_buku_baru != $id_buku_lama) { // Hanya cek jika kode buku memang diubah
            $cek_duplikat_stmt = mysqli_prepare($conn, "SELECT KD_BUKU FROM buku WHERE KD_BUKU = ?");
            mysqli_stmt_bind_param($cek_duplikat_stmt, "s", $kode_buku_baru);
            mysqli_stmt_execute($cek_duplikat_stmt);
            mysqli_stmt_store_result($cek_duplikat_stmt);

            if (mysqli_stmt_num_rows($cek_duplikat_stmt) > 0) {
                $_SESSION['message'] = "Gagal update: Kode Buku baru '$kode_buku_baru' sudah digunakan oleh buku lain.";
                $_SESSION['message_type'] = "error";
                mysqli_stmt_close($cek_duplikat_stmt);
                // Redirect kembali ke form edit dengan ID LAMA
                header("Location: index.php?menu=input_buku&aksi=edit&id=" . urlencode($id_buku_lama));
                exit;
            }
            mysqli_stmt_close($cek_duplikat_stmt);
        }

        // Lanjutkan dengan UPDATE
        // Perhatikan: KD_BUKU sekarang ada di bagian SET dan WHERE clause menggunakan $id_buku_lama
        $stmt = mysqli_prepare($conn, "UPDATE buku SET KD_BUKU = ?, JUDUL = ?, PENGARANG = ?, PENERBIT = ? WHERE KD_BUKU = ?");
        mysqli_stmt_bind_param($stmt, "sssss", $kode_buku_baru, $judul_buku, $pengarang_buku, $penerbit_buku, $id_buku_lama);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['message'] = "Data buku berhasil diupdate.";
                $_SESSION['message_type'] = "success";
            } else {
                // Ini bisa terjadi jika tidak ada perubahan data, atau jika $id_buku_lama tidak ditemukan (seharusnya tidak terjadi jika alur benar)
                $_SESSION['message'] = "Tidak ada perubahan pada data buku atau buku tidak ditemukan dengan kode lama.";
                $_SESSION['message_type'] = "info";
            }
        } else {
            // Jika query gagal, kemungkinan karena KD_BUKU baru melanggar constraint unik (meskipun sudah dicek, ada race condition kecil)
            // atau error SQL lainnya.
            $_SESSION['message'] = "Gagal mengupdate data buku: " . mysqli_stmt_error($stmt) . ". Pastikan Kode Buku baru unik.";
            $_SESSION['message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Aksi tidak valid.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: index.php?menu=tampil_buku");
    exit;
} else {
    $_SESSION['message'] = "Akses tidak sah ke skrip simpan.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php?menu=tampil_buku");
    exit;
}
