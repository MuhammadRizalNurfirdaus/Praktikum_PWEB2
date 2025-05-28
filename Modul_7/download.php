<?php
require_once "koneksi.php";

if (isset($_GET['id'])) {
    $file_id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn_upload, "SELECT name, type, size FROM upload WHERE id = ?");
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
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $fileType);
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $fileSize);

            if (ob_get_level()) {
                ob_end_clean();
            }

            readfile($filepath);
            mysqli_close($conn_upload);
            exit;
        } else {
            echo "Error: File fisik tidak ditemukan di server.";
        }
    } else {
        echo "Error: File dengan ID tersebut tidak ditemukan di database.";
    }
} else {
    echo "Error: ID file tidak diberikan.";
}
mysqli_close($conn_upload);
