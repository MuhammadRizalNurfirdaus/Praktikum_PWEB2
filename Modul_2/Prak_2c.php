<?php
$kalimat = "Saya belajar PHP";
$kata = explode(" ", $kalimat);
$jumlahArray = count($kata);

for ($i = 0; $i < $jumlahArray; $i++) {
    echo "Kata[$i] = $kata[$i]<br>";
}

$gabung = implode(" ", $kata);
echo "<br>Hasil Penggabungan = $gabung";
?>