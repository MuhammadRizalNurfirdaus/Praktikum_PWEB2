<?php
include "classMawar.php";

class MawarPutih extends Mawar {
    public function __construct($tumbuh, $batang, $harum) {
        parent::__construct($tumbuh, $batang, $harum, "Putih");
    }

    public function tampil() {
        echo "<br>Jenis Mawar: Mawar Putih";
        parent::tampil();
    }
}
?>
