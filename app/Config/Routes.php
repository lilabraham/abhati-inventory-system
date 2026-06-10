<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('produk', 'ProdukController::index');
$routes->post('produk/store', 'ProdukController::store');
$routes->post('produk/delete/(:num)', 'ProdukController::delete/$1');
$routes->get('produk/edit/(:num)', 'ProdukController::edit/$1');
$routes->post('produk/update/(:num)', 'ProdukController::update/$1');