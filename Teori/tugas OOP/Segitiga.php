<?php
require_once 'BangunDatar.php';

class Segitiga extends BangunDatar
{
    protected $alas;
    protected $tinggi;

    public function __construct($alas, $tinggi)
    {
        parent::__construct('Segitiga Siku-Siku');
        $this->alas = $alas;
        $this->tinggi = $tinggi;
    }

    public function hitungLuas()
    {
        return 0.5 * $this->alas * $this->tinggi;
    }

    public function hitungKeliling()
    {
        $sisiMiring = sqrt(pow($this->alas, 2) + pow($this->tinggi, 2));
        return $this->alas + $this->tinggi + $sisiMiring;
    }
}
