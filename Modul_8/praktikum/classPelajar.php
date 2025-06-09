<?php
include 'classManusia.php';
class Pelajar extends Manusia {
    private $nim;
    private $asalSekolah;
    private $nilai;
    public function __construct($a, $b, $c, $d, $e, $f) {
        parent::__construct($a, $b, $c);
        $this->nim = $d;
        $this->asalSekolah = $e;
        $this->nilai = $f;
    }
    public function Tampil(){
        echo "<br>NIM: " . $this->nim;
        echo "<br>Sekolah: " . $this->asalSekolah;
        echo "<br>Nilai: " . $this->nilai;
    }
}
?>