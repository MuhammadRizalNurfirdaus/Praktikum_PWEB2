<?php
include "classProgrammer.php";
$rizal = new Programmer('Muhammad Rizal Nurfirdaus');
echo ("Nama: " . $rizal->tampilkanNama() . "<br>");
$rizal->makan();
$rizal->kerja();
$rizal->bersantai();
