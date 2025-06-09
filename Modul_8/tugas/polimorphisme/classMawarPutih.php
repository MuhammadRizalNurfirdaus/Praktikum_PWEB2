<?php
require_once "classMawar.php";

class MawarPutih extends Mawar
{
    public function tampil()
    {
        echo "Jenis Mawar: Mawar Putih<br>";
        echo "Tumbuh: $this->tumbuh<br>";
        echo "Batang: $this->batang<br>";
        echo "Harum: $this->harum<br>";
        echo "Warna: Putih<br><br>";
    }
}
