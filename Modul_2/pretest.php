<?php
// Fungsi untuk menghitung luas persegi panjang
function hitungLuasPersegiPanjang($panjang, $lebar)
{
    return $panjang * $lebar;
}

// Contoh penggunaan fungsi
$panjang = 10;
$lebar = 5;
$luas = hitungLuasPersegiPanjang($panjang, $lebar);

echo "Luas persegi panjang dengan panjang $panjang dan lebar $lebar adalah: $luas";
?>