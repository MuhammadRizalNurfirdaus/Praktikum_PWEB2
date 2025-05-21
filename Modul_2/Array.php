<?php
$data = array(4, 2, 8, 6);

// Mengurutkan array
sort($data);

// Menampilkan total dari seluruh elemen
echo array_sum($data); // Output: 20

// Menghitung jumlah elemen
echo count($data); // Output: 4

// Mencari nilai tertinggi dan terendah
echo max($data); // Output: 8
echo min($data); // Output: 2
?>