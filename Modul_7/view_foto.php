<?php
require_once "koneksi.php";

if (isset($_GET['id'])) {
    $file_id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn_upload, "SELECT name, type, size FROM upload WHERE id = ? AND type LIKE 'image/%'");
    mysqli_stmt_bind_param($stmt, "i", $file_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fileName, $fileType, $fileSize);
    $fileFound = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($fileFound && $fileName) {
        $filepath = 'data/' . $fileName;
        if (basename($fileName) != $fileName) {
            die("Nama file tidak valid.");
        }

        if (file_exists($filepath)) {
            header('Content-Type: ' . $fileType);
            header('Content-Length: ' . $fileSize);

            if (ob_get_level()) {
                ob_end_clean();
            }
            readfile($filepath);
            mysqli_close($conn_upload);
            exit;
        } else {
            http_response_code(404);
            echo "Error: File fisik foto tidak ditemukan di server.";
        }
    } else {
        http_response_code(404);
        echo "Error: Foto dengan ID tersebut tidak ditemukan atau bukan tipe gambar.";
    }
} else {
    http_response_code(400);
    echo "Error: ID foto tidak diberikan.";
}
mysqli_close($conn_upload);
