<?php
if (isset($_GET['id']) && isset($conn) && $conn) {
    $id_buku_hapus = mysqli_real_escape_string($conn, $_GET['id']);

    $stmt = mysqli_prepare($conn, "DELETE FROM buku WHERE KD_BUKU = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_buku_hapus);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['message'] = "Data buku (Kode: $id_buku_hapus) berhasil dihapus.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal menghapus: Data buku (Kode: $id_buku_hapus) tidak ditemukan.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Gagal menghapus data buku: " . mysqli_stmt_error($stmt);
        $_SESSION['message_type'] = "error";
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = "Permintaan hapus tidak valid atau koneksi database gagal.";
    $_SESSION['message_type'] = "error";
}

header("Location: index.php?menu=tampil_buku");
exit;
