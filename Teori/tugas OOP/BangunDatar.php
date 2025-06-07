<?php

abstract class BangunDatar
{
    protected $nama;

    public function __construct($nama)
    {
        $this->nama = $nama;
    }

    public function getNama()
    {
        return $this->nama;
    }

    abstract public function hitungLuas();
    abstract public function hitungKeliling();
}
