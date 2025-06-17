<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    public function login()
    {
        // TAMBAHKAN BARIS INI UNTUK MEMUAT FORM HELPER
        helper(['form']);

        // Jika sudah login, redirect ke halaman mahasiswa
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/mahasiswa');
        }
        return view('login');
    }

    public function prosesLogin()
    {
        $session = session();
        $model = new AdminModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $model->where('username', $username)->first();

        if ($data) {
            $pass = $data['password'];
            // Untuk praktikum ini, kita anggap password tidak di-hash
            if ($pass == $password) {
                $ses_data = [
                    'id'         => $data['id'],
                    'username'   => $data['username'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to('/mahasiswa');
            }
        }
        $session->setFlashdata('error', 'Username atau Password salah');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
