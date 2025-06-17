<?php

namespace App\Controllers;

use App\Models\M_mahasiswa; // Panggil model yang sudah dibuat

class C_mahasiswa extends BaseController
{
    // Fungsi ini akan dijalankan saat C_mahasiswa diakses
    public function index()
    {
        $model = new M_mahasiswa();
        $data['mahasiswa'] = $model->ambilData(); // Ambil data dari model
        return view('v_mahasiswa', $data); // Tampilkan ke view v_mahasiswa
    }

    // Fungsi untuk menampilkan form dan memproses data
    public function tambah()
    {
        // Jika metode request bukan 'post', tampilkan form
        if ($this->request->getMethod() !== 'post') {
            return view('tambah_mahasiswa');
        }

        // Jika metode adalah 'post', proses datanya
        $model = new M_mahasiswa();
        $data_mahasiswa = [
            'NIM'    => $this->request->getPost('nim'),
            'NAMA'   => $this->request->getPost('nama'),
            'ALAMAT' => $this->request->getPost('alamat'),
        ];

        // Simpan data ke database melalui model
        if ($model->simpanData($data_mahasiswa)) {
            // Jika berhasil, kembali ke halaman utama dengan pesan sukses
            return redirect()->to(base_url('c_mahasiswa'))->with('pesan', 'Data Mahasiswa Berhasil Disimpan');
        }
    }
}
