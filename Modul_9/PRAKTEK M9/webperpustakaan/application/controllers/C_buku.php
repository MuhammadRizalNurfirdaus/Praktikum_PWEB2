<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_Session $session
 * @property M_buku $m_buku
 */
class C_buku extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Karena library 'database', 'session', dan helper 'url', 'form'
        // sudah di-autoload, baris-baris ini tidak diperlukan lagi.
        $this->load->model('M_buku', 'm_buku'); 
    }

    // Method untuk menampilkan daftar buku
    public function index()
    {
        $data['buku'] = $this->m_buku->get_all_buku();
        $this->load->view('tampil_buku', $data);
    }

    public function tambahdata()
    {
        // Pengecekan method POST lebih disarankan daripada nama tombol submit
        if ($this->input->server('REQUEST_METHOD') === 'POST') { 
            // Panggil method tambah dari model
            if ($this->m_buku->tambah()) {
                // Jika berhasil, buat notifikasi sukses
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Buku Berhasil Disimpan!</div>');
            } else {
                // Jika gagal, buat notifikasi error
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Buku Gagal Disimpan.</div>');
            }
            // Alihkan ke halaman utama untuk melihat daftar dan notifikasi
            redirect('c_buku'); 
        }
        
        // Jika tidak submit form (method GET), tampilkan halaman form
        $this->load->view('tambah_buku'); 
    }
}