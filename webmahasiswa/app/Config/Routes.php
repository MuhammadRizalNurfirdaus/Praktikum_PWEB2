<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('mahasiswa', 'Mahasiswa::index');
$routes->get('mahasiswa/tambah', 'Mahasiswa::tambah');
$routes->post('mahasiswa/simpan', 'Mahasiswa::simpan');

$routes->get('/mahasiswa/edit/(:segment)', 'Mahasiswa::edit/$1');
$routes->post('/mahasiswa/update', 'Mahasiswa::update');

$routes->get('/mahasiswa/delete/(:segment)', 'Mahasiswa::delete/$1');

$routes->get('/login', 'Auth::login');
$routes->post('/auth/prosesLogin', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');
