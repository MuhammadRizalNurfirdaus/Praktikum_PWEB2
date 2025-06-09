<?php
include "classMawarMerah.php";
include "classMawarPutih.php";

$daftarMawar = [
    new MawarMerah("Semak", "Berduri", "Wangi"),
    new MawarPutih("Semak", "Lurus", "Lembut")
];

foreach ($daftarMawar as $mawar) {
    $mawar->tampil();
}
