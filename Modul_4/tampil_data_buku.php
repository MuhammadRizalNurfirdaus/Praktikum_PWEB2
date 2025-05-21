<?php
$q = "SELECT * FROM buku";
$conn = mysqli_connect("localhost", "root", "", "perpustakaan")
    or die("Koneksi gagal: " . mysqli_connect_error());
$r = mysqli_query($conn, $q) or die(mysqli_error($conn));
$jml = mysqli_num_rows($r);
