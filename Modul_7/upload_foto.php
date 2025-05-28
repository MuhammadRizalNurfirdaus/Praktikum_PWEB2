<?php
require_once "koneksi.php";

$uploaddir = 'data/';
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size_bytes = 5 * 1024 * 1024;

if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {

    $fileType = $_FILES['userfile']['type'];
    $fileSize = $_FILES['userfile']['size'];

    if (!in_array($fileType, $allowed_types)) {
        die("Error: Tipe file tidak diizinkan. Hanya file JPG, PNG, dan GIF yang boleh diupload. <a href='form_foto.html'>Kembali</a>");
    }

    if ($fileSize > $max_size_bytes) {
        die("Error: Ukuran file terlalu besar (Maks: 5MB). <a href='form_foto.html'>Kembali</a>");
    }

    $baseFileName = basename($_FILES['userfile']['name']);
    $safeFileName = preg_replace("/[^a-zA-Z0-9._-]/", "_", $baseFileName);

    $prefix = uniqid_real(8) . '_';
    $finalFileName = $prefix . $safeFileName;

    $tmpName = $_FILES['userfile']['tmp_name'];

    $stmt_check = mysqli_prepare($conn_upload, "SELECT id FROM upload WHERE name = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $finalFileName);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "Error: File dengan nama '$finalFileName' sudah ada. Silakan ganti nama file atau coba lagi.<br>";
    } else {
        $stmt_insert = mysqli_prepare($conn_upload, "INSERT INTO upload (name, size, type) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "sis", $finalFileName, $fileSize, $fileType);
        if (mysqli_stmt_execute($stmt_insert)) {
            // 
        } else {
            echo "Gagal menyimpan informasi foto '$finalFileName' ke database: " . mysqli_stmt_error($stmt_insert) . "<br>";
        }
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_stmt_close($stmt_check);

    $uploadfile = $uploaddir . $finalFileName;

    if (move_uploaded_file($tmpName, $uploadfile)) {
        echo "Foto '" . htmlspecialchars($finalFileName) . "' telah berhasil diupload.";
        echo "<br><img src='" . $uploadfile . "' alt='Foto terupload' style='max-width:200px; max-height:200px; margin-top:10px;' />";
        echo "<div style='font-size:0.9em; color:#555; margin-top:5px;'>";
        echo "Nama File: " . htmlspecialchars($finalFileName) . "<br>";
        echo "Ukuran: " . round($fileSize / 1024, 2) . " KB<br>";
        echo "Tipe: " . htmlspecialchars($fileType);
        echo "</div>";
    } else {
        echo "Foto gagal dipindahkan ke direktori tujuan.";
    }
} else {
    $errorMessage = "Terjadi kesalahan saat upload foto. ";
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
                $errorMessage .= "Error tidak diketahui (kode error: " . $_FILES['userfile']['error'] . ").";
                break;
        }
    } else {
        $errorMessage .= "Tidak ada file yang dipilih atau form tidak dikirim dengan benar.";
    }
    echo $errorMessage;
}

echo "<br><p><a href='form_foto.html'>Upload foto lain</a> | <a href='list_foto.php'>Lihat Daftar Foto</a></p>";
mysqli_close($conn_upload);

function uniqid_real($length = 13)
{
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($length / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
    } else {
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
    }
    return substr(bin2hex($bytes), 0, $length);
}
