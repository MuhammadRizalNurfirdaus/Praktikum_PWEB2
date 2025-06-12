<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_DB_query_builder $db
 */
class M_buku extends CI_Model {

    /**
     * Menyimpan data buku baru ke dalam database.
     * Mengembalikan true jika berhasil, false jika gagal.
     *
     * @return bool
     */
    public function tambah()
    {
        // Ambil data dari form input
        $kode_buku      = $this->input->post('kd_buku', true);
        $judul_buku     = $this->input->post('judul_buku', true);
        $pengarang_buku = $this->input->post('pengarang_buku', true);
        $penerbit_buku  = $this->input->post('penerbit_buku', true);
        
        // Siapkan array dengan NAMA KOLOM YANG BENAR (huruf kecil)
        $data = array(
            'kode_buku'      => $kode_buku,
            'judul_buku'     => $judul_buku,
            'pengarang_buku' => $pengarang_buku,
            'penerbit_buku'  => $penerbit_buku
        );

        // Lakukan insert dan kembalikan hasilnya (true atau false)
        return $this->db->insert('buku', $data);
    }

    public function get_all_buku()
    {
        return $this->db->get('buku')->result_array();
    }
}
