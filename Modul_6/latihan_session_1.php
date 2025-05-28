<?php
session_start();
$nama = "Ratna";
$_SESSION['nama_user'] = $nama;
$user = $_SESSION['nama_user'];

include "latihan_session_2.php";
