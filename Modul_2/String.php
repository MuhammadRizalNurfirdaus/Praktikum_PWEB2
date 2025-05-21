<?php
$teks = "Halo Dunia";

// Menghitung panjang string
echo strlen($teks); // Output: 10

// Mengubah huruf menjadi huruf besar
echo strtoupper($teks); // Output: HALO DUNIA

// Mengubah huruf menjadi huruf kecil
echo strtolower($teks); // Output: halo dunia

// Mengambil sebagian string
echo substr($teks, 5); // Output: Dunia
?>