<?php
namespace App\Controllers;
use App\Models\MahasiswaModel;
use CodeIgniter\Controller;

class Mahasiswa extends Controller
{
    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            header('Location: ' . base_url('/login'));
            exit;
        }
    }
    public function index()
    {
        $model = new MahasiswaModel();
        $data['mahasiswa'] = $model->findAll();
        return view('mahasiswa_list', $data);
    }
    public function tambah()
    {
        return view('mahasiswa_form');
    }
    public function edit($nim)
    {
        $model = new MahasiswaModel();
        $data['mahasiswa'] = $model->find($nim);
        return view('mahasiswa_form_edit', $data);
    }
    public function update()
    {
        $model = new MahasiswaModel();
        $nim = $this->request->getPost('nim');
        $data = [
            'nama'   => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
        ];
        $model->update($nim, $data);
        return redirect()->to('/mahasiswa');
    }
    public function delete($nim)
    {
        $model = new MahasiswaModel();
        $model->delete($nim);
        return redirect()->to('/mahasiswa');
    }
    public function simpan()
    {
        $model = new MahasiswaModel();
        $data = [
            'nim'    => $this->request->getPost('nim'),
            'nama'   => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
        ];
        $model->insert($data);
        return redirect()->to('/mahasiswa');
    }
}
?>