<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('login_form');
    }
    public function prosesLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        if ($username === 'admin' && $password === '12345') {
            session()->set('isLoggedIn', true);
            session()->set('username', $username);
            return redirect()->to('/mahasiswa');
        } else {
            return redirect()->back()->with('error', 'Username atau password salah');
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
?>