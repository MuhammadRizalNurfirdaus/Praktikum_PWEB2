<?php
include "classTentara.php";
$rizal = new Tentara("Muhammad Rizal Nurfirdaus", "Kopral");
echo ("Nama: " . $rizal->tampilkanNama() . "<br>");
echo ("Pangkat: " . $rizal->tampilkanPangkat() . "<br>");
$rizal->makan();
$rizal->kerja();
