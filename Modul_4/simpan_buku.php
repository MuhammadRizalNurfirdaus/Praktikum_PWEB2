<?php
extract($_POST);
if ($submit == "SIMPAN") {
    $q = "INSERT INTO buku VALUES ('$kode_buku', '$judul_buku', '$pengarang_buku', '$penerbit_buku')";
    $conn = new mysqli("localhost", "root", "", "perpustakaan");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $r = $conn->query($q);
    if (!$r) {
        die("Error: " . $conn->error);
    }
    if ($r) {
        $msg = "Data sudah ditambahkan";
    } else {
        $msg = "Data tidak bisa dimasukkan";
    }
    echo "$msg";
}
