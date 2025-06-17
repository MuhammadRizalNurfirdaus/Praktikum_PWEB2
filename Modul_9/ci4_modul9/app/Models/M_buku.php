<?php

namespace App\Models;

use CodeIgniter\Model;

class M_buku extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'KD_BUKU';
    protected $allowedFields    = ['KD_BUKU', 'JUDUL', 'PENGARANG', 'PENERBIT'];

    public function ambilbuku()
    {
        return $this->findAll();
    }

    public function tambah($data)
    {
        return $this->insert($data);
    }
}
