<?php
require_once "classMawar.php";

class MawarMerah extends Mawar
{
    public function tampil()
    {
        echo "Jenis Mawar: Mawar Merah<br>";
        echo "Tumbuh: $this->tumbuh<br>";
        echo "Batang: $this->batang<br>";
        echo "Harum: $this->harum<br>";
        echo "Warna: Merah<br><br>";
    }
}
