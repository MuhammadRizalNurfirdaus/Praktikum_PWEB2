<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 // di app/Config/Routes.php, di awal file
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('cMahasiswa');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);



