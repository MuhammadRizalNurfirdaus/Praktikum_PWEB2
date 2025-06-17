<?php
namespace App\Models;

use CodeIgniter\Model;

class mMahasiswa extends Model {
    protected $table = 'mahasiswa';
    protected $primaryKey = 'NIM';
    protected $allowedFields = ['NIM', 'NAMA', 'FAKULTAS', 'JURUSAN', 'JENJANG'];

    public function tambah() {
        $request = \Config\Services::request();
        $data = [
            'NIM' => $request->getPost('nim'),
            'NAMA' => $request->getPost('nama'),
            'FAKULTAS' => $request->getPost('fakultas'),
            'JURUSAN' => $request->getPost('jurusan'),
            'JENJANG' => $request->getPost('jenjang'),
        ];
        $this->insert($data);
    }

    public function ambil() {
        return $this->findAll();
    }

    public function select($NIM) {
        return $this->asArray()->where('NIM', $NIM)->first();
    }

    public function updateData($NIM) {
        $request = \Config\Services::request();
        $data = [
            'NAMA' => $request->getPost('nama'),
            'FAKULTAS' => $request->getPost('fakultas'),
            'JURUSAN' => $request->getPost('jurusan'),
            'JENJANG' => $request->getPost('jenjang'),
        ];
        $this->update($NIM, $data);
    }

    public function deleteData($NIM) {
        return $this->delete($NIM);
    }
}
