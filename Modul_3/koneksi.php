<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "db_modul3";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo "Koneksi ke database berhasil!";
?>