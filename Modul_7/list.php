<?php
// list.php
require_once "koneksi.php"; // Pastikan ini menggunakan koneksi.php versi mysqli_* dan $conn_upload

echo "<!DOCTYPE html><html><head><title>Daftar File</title></head><body>";
echo "<h2>Daftar File Tersedia</h2>";

$query = "SELECT id, name, size FROM upload ORDER BY name ASC";
$result = mysqli_query($conn_upload, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        $fileId = htmlspecialchars($row['id']);
        $fileName = htmlspecialchars($row['name']);
        $fileSize = htmlspecialchars($row['size']);

        echo "<li>";
        // Link ke skrip download.php
        echo "<a href='download.php?id=" . $fileId . "'>" . $fileName . "</a> ";
        echo "(Ukuran: " . round($fileSize / 1024, 2) . " KB)";
        // Link ke skrip delete.php (nama file disesuaikan menjadi delete.php)
        echo " <a href='delete.php?id=" . $fileId . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus file ini: " . addslashes($fileName) . "?\");'>Delete</a>";
        echo "</li>";
    } // Penutup kurung kurawal while
    echo "</ul>";
} else {
    echo "<p>Tidak ada file yang tersedia.</p>";
}

echo "<br><a href='form.html'>Kembali ke Form Upload</a>";
echo "</body></html>";

mysqli_close($conn_upload);
