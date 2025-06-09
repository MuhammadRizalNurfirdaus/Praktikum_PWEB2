<?php

class SepedaMotor {
    private $merk, $tipe;
    private $tangki;
    private $harga;

    function setMerk($m) {
        $this -> merk = $m;
    }
    function getMerk() {
        return $this -> merk;
    }
}
?>