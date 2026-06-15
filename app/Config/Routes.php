<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Shield default auth routes (login, logout, register)
service('auth')->routes($routes);

// ============================================================
// WEB ROUTES — dilindungi global session filter
// ============================================================
$routes->get('/', 'AssetController::index');
$routes->get('data-aset', 'AssetController::index'); 
$routes->get('data-aset/(:num)', 'AssetController::show/$1'); 
$routes->get('it-support', 'SupportController::index');

// ── NEW: Web UI Pusat Laporan ──
$routes->get('laporan', 'ReportController::index'); 

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

    // ── NEW: Centralized Report API ──
    $routes->get('reports/summary', 'ReportController::summary');
    $routes->get('reports/assets', 'ReportController::assets');
    
    // Endpoint khusus untuk export dokumen terpusat
    $routes->post('reports/generate', 'ReportController::generate'); // Jika menggunakan submit form
    $routes->get('reports/export-excel', 'ReportController::exportExcel'); // Jika menggunakan link langsung
    // $routes->get('reports/export-pdf', 'ReportController::exportPdf');
});