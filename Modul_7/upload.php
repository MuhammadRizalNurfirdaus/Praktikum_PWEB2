<?php
require_once "koneksi.php";

$uploaddir = 'data/';

if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
    $baseFileName = basename($_FILES['userfile']['name']);
    $safeFileName = preg_replace("/[^a-zA-Z0-9._-]/", "_", $baseFileName);
    $finalFileName = $safeFileName;

    $fileSize  = $_FILES['userfile']['size'];
    $fileType  = $_FILES['userfile']['type'];
    $tmpName   = $_FILES['userfile']['tmp_name'];

    $stmt_check = mysqli_prepare($conn_upload, "SELECT id FROM upload WHERE name = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $finalFileName);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $stmt_update = mysqli_prepare($conn_upload, "UPDATE upload SET size = ?, type = ? WHERE name = ?");
        mysqli_stmt_bind_param($stmt_update, "iss", $fileSize, $fileType, $finalFileName);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);
    } else {
        $stmt_insert = mysqli_prepare($conn_upload, "INSERT INTO upload (name, size, type) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sis", $finalFileName, $fileSize, $fileType);
        mysqli_stmt_execute($stmt_insert);
        if (mysqli_stmt_affected_rows($stmt_insert) <= 0) {
            echo "Gagal menyimpan informasi file '$finalFileName' ke database.<br>";
        }
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);

    $uploadfile = $uploaddir . $finalFileName;

    if (move_uploaded_file($tmpName, $uploadfile)) {
        echo "File '$finalFileName' telah berhasil diupload ke server dan informasi disimpan ke database.";
        echo "<br><a href='form.html'>Upload file lain</a>";
        echo "<br><a href='download.php'>Lihat daftar file (Praktek 3)</a>";
    } else {
        echo "File gagal dipindahkan ke direktori tujuan.";
    }
} else {
    $errorMessage = "Terjadi kesalahan saat upload file. ";
    if (isset($_FILES['userfile']['error'])) {
        switch ($_FILES['userfile']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $errorMessage .= "Ukuran file melebihi batas yang diizinkan oleh server (upload_max_filesize).";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $errorMessage .= "Ukuran file melebihi batas yang ditentukan dalam form HTML (MAX_FILE_SIZE).";
                break;
            case UPLOAD_ERR_PARTIAL:
                $errorMessage .= "File hanya terupload sebagian.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $errorMessage .= "Tidak ada file yang diupload.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $errorMessage .= "Folder temporary tidak ditemukan.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $errorMessage .= "Gagal menulis file ke disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $errorMessage .= "Upload file dihentikan oleh ekstensi PHP.";
                break;
            default:
                $errorMessage .= "Error tidak diketahui.";
                break;
        }
    } else {
        $errorMessage .= "Tidak ada file yang dipilih atau terjadi error lain.";
    }
    echo $errorMessage;
    echo "<br><a href='form.html'>Kembali ke form upload</a>";
}

mysqli_close($conn_upload);
