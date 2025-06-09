<?php
class Mawar
{
    private $tumbuh;
    private $batang;
    private $harum;
    private $warna;

    public function __construct($tumbuh, $batang, $harum, $warna)
    {
        $this->tumbuh = $tumbuh;
        $this->batang = $batang;
        $this->harum = $harum;
        $this->warna = $warna;
    }

    public function tampil()
    {
        echo "<br>Tumbuh: $this->tumbuh";
        echo "<br>Batang: $this->batang";
        echo "<br>Harum: $this->harum";
        echo "<br>Warna: $this->warna";
    }
}
