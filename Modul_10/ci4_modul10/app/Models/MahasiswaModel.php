<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'NIM';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['NIM', 'NAMA', 'ALAMAT'];
}
