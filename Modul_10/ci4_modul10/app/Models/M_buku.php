<?php

namespace App\Models;

use CodeIgniter\Model;

class M_buku extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'KD_BUKU';
    protected $useAutoIncrement = false; // Karena KD_BUKU bukan auto increment
    protected $returnType       = 'array'; // Mengembalikan data sebagai array
    protected $allowedFields    = ['KD_BUKU', 'JUDUL', 'PENGARANG', 'PENERBIT'];

    // Fungsi untuk mengambil semua buku
    public function ambilBuku()
    {
        return $this->findAll();
    }

    // Fungsi untuk mengambil satu buku berdasarkan ID
    public function pilihBuku($id)
    {
        return $this->find($id);
    }
}
