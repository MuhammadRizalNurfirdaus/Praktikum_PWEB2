<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "dbUpDown";

$conn_upload = mysqli_connect($host, $user, $password, $database);

if (!$conn_upload) {
    die("Koneksi ke database dbUpDown gagal: " . htmlspecialchars(mysqli_connect_error()) .
        "<br>Pastikan server MySQL berjalan dan detail koneksi benar. Database yang dicari: '" . htmlspecialchars($database) . "'");
}

mysqli_set_charset($conn_upload, "utf8mb4");
