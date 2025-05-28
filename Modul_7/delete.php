<?php
require_once "koneksi.php";

if (isset($_GET['id'])) {
    $file_id = intval($_GET['id']);

    mysqli_autocommit($conn_upload, false);

    $fileName = null;
    $stmt_select = mysqli_prepare($conn_upload, "SELECT name FROM upload WHERE id = ?");
    mysqli_stmt_bind_param($stmt_select, "i", $file_id);
    mysqli_stmt_execute($stmt_select);
    mysqli_stmt_bind_result($stmt_select, $fileName);
    $fileFound = mysqli_stmt_fetch($stmt_select);
    mysqli_stmt_close($stmt_select);

    if ($fileFound && $fileName) {
        $stmt_delete = mysqli_prepare($conn_upload, "DELETE FROM upload WHERE id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $file_id);
        $delete_db_success = mysqli_stmt_execute($stmt_delete);
        $affected_rows = mysqli_stmt_affected_rows($stmt_delete);
        mysqli_stmt_close($stmt_delete);

        if ($delete_db_success && $affected_rows > 0) {
            $filepath = 'data/' . $fileName;
            if (basename($fileName) != $fileName) {
                mysqli_rollback($conn_upload);
                mysqli_autocommit($conn_upload, true);
                mysqli_close($conn_upload);
                die("Nama file tidak valid untuk dihapus.");
            }

            if (file_exists($filepath)) {
                if (unlink($filepath)) {
                    mysqli_commit($conn_upload);
                    echo "File '" . htmlspecialchars($fileName) . "' dan record database telah berhasil dihapus.";
                } else {
                    mysqli_rollback($conn_upload);
                    echo "Gagal menghapus file fisik '" . htmlspecialchars($fileName) . "' dari server. Record database tidak dihapus.";
                }
            } else {
                mysqli_commit($conn_upload);
                echo "Record database untuk file '" . htmlspecialchars($fileName) . "' telah dihapus, namun file fisik tidak ditemukan di server (mungkin sudah dihapus sebelumnya).";
            }
        } else {
            mysqli_rollback($conn_upload);
            echo "Gagal menghapus record dari database atau file dengan ID tersebut tidak ditemukan.";
        }
    } else {
        echo "Error: File dengan ID tersebut tidak ditemukan di database untuk dihapus.";
    }
    mysqli_autocommit($conn_upload, true);
} else {
    echo "Error: ID file tidak diberikan untuk dihapus.";
}

echo "<br><a href='list.php'>Kembali ke Daftar File</a>";
if (isset($_GET['ref']) && $_GET['ref'] == 'list_foto') {
    echo "<br><a href='list_foto.php'>Kembali ke Daftar Foto</a>";
}

mysqli_close($conn_upload);
