<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

class Mahasiswa extends BaseController
{
    /**
     * Menampilkan semua data mahasiswa
     */
    public function index()
    {
        $model = new MahasiswaModel();
        $data = [
            'mahasiswa' => $model->findAll(),
            'title' => 'Data Mahasiswa'
        ];
        return view('v_mahasiswa', $data);
    }

    /**
     * Menampilkan form tambah data
     */
    public function tambah()
    {
        $data['title'] = 'Tambah Data Mahasiswa';
        return view('tambah_mahasiswa', $data);
    }

    /**
     * Menyimpan data baru
     */
    public function simpan()
    {
        $model = new MahasiswaModel();
        $data = [
            'NIM'    => $this->request->getPost('nim'),
            'NAMA'   => $this->request->getPost('nama'),
            'ALAMAT' => $this->request->getPost('alamat'),
        ];
        $model->insert($data);
        return redirect()->to('/mahasiswa')->with('pesan', 'Data Berhasil Disimpan.');
    }

    /**
     * Menampilkan form edit berdasarkan NIM
     */
    public function edit($nim)
    {
        $model = new MahasiswaModel();
        $data = [
            'mahasiswa' => $model->find($nim),
            'title' => 'Edit Data Mahasiswa'
        ];
        return view('edit_mahasiswa', $data);
    }

    /**
     * Memproses update data
     */
    public function update($nim)
    {
        $model = new MahasiswaModel();
        $data = [
            'NAMA'   => $this->request->getPost('nama'),
            'ALAMAT' => $this->request->getPost('alamat'),
        ];
        $model->update($nim, $data);
        return redirect()->to('/mahasiswa')->with('pesan', 'Data Berhasil Diperbarui.');
    }

    /**
     * Menghapus data berdasarkan NIM
     */
    public function delete($nim)
    {
        $model = new MahasiswaModel();
        $model->delete($nim);
        return redirect()->to('/mahasiswa')->with('pesan', 'Data Berhasil Dihapus.');
    }
}
