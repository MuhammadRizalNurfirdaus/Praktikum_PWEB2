<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\mMahasiswa;

class cMahasiswa extends Controller {
    protected $mMahasiswa;

    public function __construct() {
        helper(['form', 'url']);
        $this->mMahasiswa = new mMahasiswa();
    }

    public function index() {
        $data['records'] = $this->mMahasiswa->ambil();
        return view('vMahasiswa', $data);
    }

    public function formtambah() {
        return view('insertMahasiswa');
    }

    public function tambahdata() {
        $this->mMahasiswa->tambah();
        return redirect()->to(base_url('cMahasiswa'));
    }

    public function updatedata($NIM) {
        $request = \Config\Services::request();
        if ($request->getMethod(true) === 'POST') {
            $this->mMahasiswa->updateData($NIM);
            return redirect()->to(base_url('cMahasiswa'));
        } else {
            $data['datarecord'] = $this->mMahasiswa->select($NIM);
            return view('insertMahasiswa', $data);
        }
    }

    public function deletedata($NIM) {
        $this->mMahasiswa->deleteData($NIM);
        return redirect()->to(base_url('cMahasiswa'));
    }
}
