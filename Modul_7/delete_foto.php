<?php
require_once "koneksi.php";
session_start();

$redirect_page = 'list_foto.php';

if (isset($_GET['id'])) {
    $file_id = intval($_GET['id']);
    mysqli_autocommit($conn_upload, false);

    $fileName = null;
    $stmt_select = mysqli_prepare($conn_upload, "SELECT name FROM upload WHERE id = ? AND type LIKE 'image/%'");
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
                $_SESSION['delete_message'] = "Error: Nama file foto tidak valid.";
                $_SESSION['delete_type'] = "error";
            } else {
                if (file_exists($filepath)) {
                    if (unlink($filepath)) {
                        mysqli_commit($conn_upload);
                        $_SESSION['delete_message'] = "Foto '" . htmlspecialchars($fileName) . "' berhasil dihapus.";
                        $_SESSION['delete_type'] = "success";
                    } else {
                        mysqli_rollback($conn_upload);
                        $_SESSION['delete_message'] = "Error: Gagal menghapus file foto fisik.";
                        $_SESSION['delete_type'] = "error";
                    }
                } else {
                    mysqli_commit($conn_upload);
                    $_SESSION['delete_message'] = "Record foto dihapus, tapi file fisik tidak ditemukan.";
                    $_SESSION['delete_type'] = "warning";
                }
            }
        } else {
            mysqli_rollback($conn_upload);
            $_SESSION['delete_message'] = "Error: Gagal menghapus record foto dari database.";
            $_SESSION['delete_type'] = "error";
        }
    } else {
        $_SESSION['delete_message'] = "Error: Foto tidak ditemukan untuk dihapus.";
        $_SESSION['delete_type'] = "error";
    }
    mysqli_autocommit($conn_upload, true);
} else {
    $_SESSION['delete_message'] = "Error: ID foto tidak diberikan.";
    $_SESSION['delete_type'] = "error";
}

mysqli_close($conn_upload);
header("Location: " . $redirect_page);
exit;
