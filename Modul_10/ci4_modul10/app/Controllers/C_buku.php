<?php

namespace App\Controllers;

use App\Models\M_buku; // Pastikan untuk memanggil Model

class C_buku extends BaseController
{
    /**
     * Menampilkan semua data buku (Read)
     */
    public function index()
    {
        $model = new M_buku();
        $data['records'] = $model->ambilBuku();
        return view('v_buku', $data);
    }

    /**
     * Menampilkan form tambah dan memproses data baru (Create)
     */
    public function tambahdata()
    {
        // Jika metode bukan POST, tampilkan form tambah
        if ($this->request->getMethod() !== 'post') {
            return view('tambah_buku');
        }

        // Jika metode POST, proses data dari form
        $model = new M_buku();
        $data = [
            'KD_BUKU'   => $this->request->getPost('kd_buku'),
            'JUDUL'     => $this->request->getPost('judul_buku'),
            'PENGARANG' => $this->request->getPost('pengarang_buku'),
            'PENERBIT'  => $this->request->getPost('penerbit_buku'),
        ];

        $model->insert($data);

        return redirect()->to(site_url('c_buku'))->with('message', 'Data Berhasil Disimpan');
    }

    /**
     * Menampilkan form edit dan memproses perubahan data (Update)
     */
    public function updatedata($id)
    {
        $model = new M_buku();

        // Jika metode adalah POST, proses data update
        if ($this->request->getMethod() === 'post') {
            $data = [
                'JUDUL'     => $this->request->getPost('judul_buku'),
                'PENGARANG' => $this->request->getPost('pengarang_buku'),
                'PENERBIT'  => $this->request->getPost('penerbit_buku'),
            ];

            $model->update($id, $data);

            return redirect()->to(site_url('c_buku'))->with('message', 'Data Berhasil Diperbarui');
        }

        // Jika metode GET, tampilkan form dengan data yang ada
        $data['datarecord'] = $model->pilihBuku($id);

        // Jika data tidak ditemukan, tampilkan error 404
        if (empty($data['datarecord'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data buku dengan kode ' . $id . ' tidak ditemukan.');
        }

        return view('edit_buku', $data);
    }

    /**
     * Menghapus data berdasarkan ID (Delete)
     */
    public function deletedata($id)
    {
        $model = new M_buku();
        $model->delete($id);

        return redirect()->to(site_url('c_buku'))->with('message', 'Data Berhasil Dihapus');
    }
}
