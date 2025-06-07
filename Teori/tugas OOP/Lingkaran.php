<?php
require_once 'BangunDatar.php';

class Lingkaran extends BangunDatar
{
    protected $jariJari;

    public function __construct($jariJari)
    {
        parent::__construct('Lingkaran');
        $this->jariJari = $jariJari;
    }

    public function hitungLuas()
    {
        return M_PI * pow($this->jariJari, 2);
    }

    public function hitungKeliling()
    {
        return 2 * M_PI * $this->jariJari;
    }
}
