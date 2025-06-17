<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// TAMBAHKAN DUA BARIS INI
$routes->get('/c_mahasiswa', 'C_mahasiswa::index');
$routes->match(['get', 'post'], '/c_mahasiswa/tambah', 'C_mahasiswa::tambah');
