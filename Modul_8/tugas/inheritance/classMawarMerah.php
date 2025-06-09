<?php
include "classMawar.php";

class MawarMerah extends Mawar {
    public function __construct($tumbuh, $batang, $harum) {
        parent::__construct($tumbuh, $batang, $harum, "Merah");
    }

    public function tampil() {
        echo "<br>Jenis Mawar: Mawar Merah";
        parent::tampil();
    }
}
?>
    