<?php
require_once 'BangunDatar.php';

class Persegi extends BangunDatar
{
    protected $sisi;

    public function __construct($sisi)
    {
        parent::__construct('Persegi');
        $this->sisi = $sisi;
    }

    public function hitungLuas()
    {
        return $this->sisi * $this->sisi;
    }

    public function hitungKeliling()
    {
        return 4 * $this->sisi;
    }
}
