<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// INI ADALAH BARIS KUNCI:
// Mengatur agar halaman utama (http://localhost:8080)
// membuka controller 'Auth' dan fungsi 'login'.
$routes->get('/', 'Auth::login');


// Routes untuk Autentikasi
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::prosesLogin');
$routes->get('logout', 'Auth::logout');

// Routes untuk CRUD Mahasiswa (Grup ini akan dilindungi oleh filter)
// Filter 'auth' akan berjalan sebelum rute di dalam grup ini diakses.
$routes->group('mahasiswa', ['filter' => 'auth'], static function ($routes) {
    $routes->get('', 'Mahasiswa::index');
    $routes->get('tambah', 'Mahasiswa::tambah');
    $routes->post('simpan', 'Mahasiswa::simpan');
    $routes->get('edit/(:num)', 'Mahasiswa::edit/$1');
    $routes->post('update/(:num)', 'Mahasiswa::update/$1');
    $routes->delete('delete/(:num)', 'Mahasiswa::delete/$1');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
