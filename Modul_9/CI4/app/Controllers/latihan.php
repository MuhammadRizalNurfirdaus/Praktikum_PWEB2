<?php

namespace App\Controllers;

class Latihan extends BaseController
{
    public function index()
    {
        $data['judul'] = 'Latihan Controller';
        $data['isi'] = 'Ini adalah latihan controller di CodeIgniter 4';
        return view('latihan_view', $data);
    }
}
