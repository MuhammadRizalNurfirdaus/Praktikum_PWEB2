<?php
include "classSopir.php";
$rizal = new Sopir("Muhammad Rizal Nurfirdaus");
echo ("Nama: " . $rizal->tampilkanNama() . "<br>");
$rizal->kerja();
