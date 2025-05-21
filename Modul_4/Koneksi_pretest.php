<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "db_praktikum";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $database);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database db_praktikum gagal: " . $conn->connect_error);
} else {
    echo "Koneksi ke database db_praktikum berhasil!";
}

// Menutup koneksi
$conn->close();
