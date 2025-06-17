<?php

namespace App\Models;

use CodeIgniter\Model;

class M_mahasiswa extends Model
{
    protected $table            = 'mahasiswa';
    // Karena primary key Anda 'NIM' (bukan 'id'), perlu didefinisikan
    protected $primaryKey       = 'NIM';
    // Izinkan field mana saja yang boleh diisi
    protected $allowedFields    = ['NIM', 'NAMA', 'ALAMAT'];

    public function ambilData()
    {
        return $this->findAll();
    }

    public function simpanData($data)
    {
        return $this->insert($data);
    }
}
