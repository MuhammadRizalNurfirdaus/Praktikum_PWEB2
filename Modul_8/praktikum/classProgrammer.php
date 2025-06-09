<?php
include "classmanusia.php";
class Programmer extends Manusia
{
    public function __construct($n)
    {
        parent::__construct($n);
    }
    public function kerja()
    {
        echo "Tak.. Tak.. Klik..<br>";
    }
    public function bersantai()
    {
        echo ("Game Over, You lose...<br>");
    }
}
