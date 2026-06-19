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
$routes->group('api', ['namespace' => 'App\Controllers\API'], function ($routes) {

    // Assets
    $routes->get('assets', 'AssetAPI::index');
    $routes->get('assets/(:num)', 'AssetAPI::show/$1');
    $routes->post('assets', 'AssetAPI::create');
    $routes->post('assets/import', 'AssetAPI::importExcel');
    $routes->put('assets/(:num)', 'AssetAPI::update/$1');
    $routes->delete('assets/(:num)', 'AssetAPI::delete/$1');

    // Repairs
    $routes->get('assets/(:num)/repairs', 'RepairAPI::byAsset/$1');
    $routes->get('repairs', 'RepairAPI::index');
    $routes->post('repairs', 'RepairAPI::create');
    $routes->put('repairs/(:num)', 'RepairAPI::update/$1');
    $routes->delete('repairs/(:num)', 'RepairAPI::delete/$1');

    // ── Centralized Report API ──
    $routes->get('reports/summary', 'ReportAPI::summary');
    $routes->get('reports/assets', 'ReportAPI::assets');
    $routes->get('reports/export-excel', 'ReportAPI::exportExcel');

    // ── Audit Trail API ──
    $routes->post('audit/log', 'AuditAPI::log');
});
