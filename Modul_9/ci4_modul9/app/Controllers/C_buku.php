<?php

namespace App\Controllers;

use App\Models\M_buku;

class C_buku extends BaseController
{
    public function index()
    {
        $model = new M_buku();
        $data['records'] = $model->ambilbuku();
        return view('v_buku', $data);
    }

    public function tambahdata()
    {
        // Menampilkan form tambah data
        if ($this->request->getMethod() !== 'post') {
            return view('tambah_buku');
        }

        // Proses penyimpanan data
        $model = new M_buku();
        $data = [
            'KD_BUKU'   => $this->request->getPost('kd_buku'),
            'JUDUL'     => $this->request->getPost('judul_buku'),
            'PENGARANG' => $this->request->getPost('pengarang_buku'),
            'PENERBIT'  => $this->request->getPost('penerbit_buku'),
        ];

        if ($model->tambah($data)) {
            // Redirect kembali ke halaman utama dengan pesan sukses
            return redirect()->to(base_url('c_buku'))->with('message', 'Data Berhasil Disimpan');
        }
    }
}
