<?php
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    // File upload
    $file = $_FILES['dokumen'];
    $namaFile = $file['name'];
    $tmpFile = $file['tmp_name'];
    $ukuran = $file['size'];
    $tipe = pathinfo($namaFile, PATHINFO_EXTENSION);

    // Validasi PDF
    if ($tipe != "pdf") {
        echo "<script>alert('File harus berformat PDF.'); window.history.back();</script>";
    } elseif ($ukuran > 2 * 1024 * 1024) {
        echo "<script>alert('Ukuran file maksimal 2MB.'); window.history.back();</script>";
    } else {
        $tujuan = "uploads/" . $namaFile;
        if (move_uploaded_file($tmpFile, $tujuan)) {
            echo "<script>alert('File berhasil diupload!');</script>";
            echo "Nama: $nama<br>Email: $email<br>";
            echo "File: <a href='$tujuan' target='_blank'>$namaFile</a>";
        } else {
            echo "<script>alert('Gagal upload file.'); window.history.back();</script>";
        }
    }
} else {
    echo "<script>alert('Form belum dikirim.'); window.history.back();</script>";
}
