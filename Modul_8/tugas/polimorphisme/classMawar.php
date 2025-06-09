<?php
class Mawar
{
    protected $tumbuh;
    protected $batang;
    protected $harum;

    public function __construct($tumbuh, $batang, $harum)
    {
        $this->tumbuh = $tumbuh;
        $this->batang = $batang;
        $this->harum = $harum;
    }

    public function tampil()
    {
        echo "Ini adalah bunga mawar umum.<br>";
    }
}
