<?php
namespace App\Models;

use CodeIgniter\Model;

class m_buku extends Model {
    protected $table = 'buku';
    protected $primaryKey = 'KD_BUKU';
    protected $allowedFields = ['KD_BUKU', 'JUDUL', 'PENGARANG', 'PENERBIT'];

    public function tambah() {
        $request = \Config\Services::request();

        $kode       = $request->getPost('kd_buku');
        $judul      = $request->getPost('judul_buku');
        $pengarang  = $request->getPost('pengarang_buku');
        $penerbit   = $request->getPost('penerbit_buku');

        $data = [
            'KD_BUKU'    => $kode,
            'JUDUL'      => $judul,
            'PENGARANG'  => $pengarang,
            'PENERBIT'   => $penerbit
        ];

        $simpanbuku = $this->insert($data);

        if ($simpanbuku) {
            echo "<script>alert('Data Berhasil Disimpan');</script>";
        }
    }

    public function select($KD_BUKU) {
        return $this->asArray()->where('KD_BUKU', $KD_BUKU)->first();
    }

    public function updateData($KD_BUKU) {
        $request = \Config\Services::request();

        $data = [
            'JUDUL'      => $request->getPost('judul_buku'),
            'PENGARANG'  => $request->getPost('pengarang_buku'),
            'PENERBIT'   => $request->getPost('penerbit_buku')
        ];

        $this->update($KD_BUKU, $data);
        return true; 
    }

    public function deleteData($KD_BUKU) {
        return $this->delete($KD_BUKU);
    }

    public function ambilbuku() {
        return $this->findAll(); 
    }
}
?>