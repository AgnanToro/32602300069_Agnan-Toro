<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Duit::index');
$routes->post('/tambah', 'Duit::tambah');
$routes->post('/hapus/(:num)', 'Duit::hapus/$1');
$routes->get('/statistik', 'Statistik::index');
$routes->get('/budget', 'Budget::index');
$routes->post('/budget/tambah', 'Budget::tambah');

