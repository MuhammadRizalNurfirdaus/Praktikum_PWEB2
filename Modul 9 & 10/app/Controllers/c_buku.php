<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\m_buku;
ini_set('display_errors', 1);
error_reporting(E_ALL);

class c_buku extends Controller {
    protected $m_buku;

    public function __construct() {
        helper(['form', 'url']);
        $this->m_buku = new m_buku();
    }

    public function tambahdata() {
        $this->m_buku->tambah();
        return redirect()->to(base_url('c_buku'));
    }

    public function formtambah(){
        return view('tambah_buku');
    }

    public function updatedata($KD_BUKU) {
        $request = \Config\Services::request();

        if ($request->getMethod(true) === 'POST') {
            $updated = $this->m_buku->updateData($KD_BUKU);
            if ($updated) {
                return redirect()->to(base_url('index.php/c_buku'))->with('message', 'Update berhasil');
            } else {
                return redirect()->back()->with('error', 'Update gagal');
            }
        } else {
            $data['datarecord'] = $this->m_buku->select($KD_BUKU);
            return view('tambah_buku', $data);
        }
    }

    public function deletedata($KD_BUKU) {
        $this->m_buku->deleteData($KD_BUKU);
        return redirect()->to(base_url('c_buku'));
    }

    public function index() {
        $data['records'] = $this->m_buku->ambilbuku();
        return view('v_buku', $data);
    }
}
?>