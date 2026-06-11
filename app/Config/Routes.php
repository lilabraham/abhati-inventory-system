<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Shield default auth routes (login, logout, register)
service('auth')->routes($routes);

// ============================================================
// WEB ROUTES — dilindungi global session filter
// ============================================================
$routes->get('/', 'AssetController::index');
$routes->get('assets', 'AssetController::index');
$routes->get('assets/report', 'AssetController::report');
$routes->get('assets/(:num)', 'AssetController::show/$1');

// ============================================================
// API ROUTES — dilindungi global session filter
// ============================================================
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    // Assets
    $routes->get('assets', 'AssetController::index');
    $routes->get('assets/(:num)', 'AssetController::show/$1');
    $routes->post('assets', 'AssetController::store');
    $routes->put('assets/(:num)', 'AssetController::update/$1');
    $routes->delete('assets/(:num)', 'AssetController::destroy/$1');

    // Repairs
    $routes->get('assets/(:num)/repairs', 'RepairController::byAsset/$1');
    $routes->post('repairs', 'RepairController::store');
    $routes->put('repairs/(:num)', 'RepairController::update/$1');
    $routes->delete('repairs/(:num)', 'RepairController::destroy/$1');

    // Report
    $routes->get('report/summary', 'ReportController::summary');
});