<?php
// Membaca isi file
$isi = file_get_contents("data.txt");
echo $isi;

// Menulis ke file
file_put_contents("hasil.txt", "Ini hasil baru");
