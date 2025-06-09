<?php 
include "classManusia.php";
class Tentara extends Manusia {
    public $pangkat;
    public function __construct($n, $p) {
        parent::__construct($n);
        $this->pangkat = $p;
    }
    public function tampilkanPangkat() {
        return $this->pangkat;
    }
    public function kerja(){
        echo ("cepatkan kerja..cepatkan kerja..<br>");
    }
}
?>