<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Shield default auth routes (login, logout, register)
service('auth')->routes($routes, ['except' => ['register']]);
// ============================================================
// WEB ROUTES — dilindungi global session filter
// ============================================================
$routes->get('/', 'AssetController::index', ['filter' => 'session']);
$routes->get('data-aset', 'AssetController::index', ['filter' => 'session']);
$routes->get('data-aset/(:num)', 'AssetController::show/$1', ['filter' => 'session']);
$routes->get('user-management', 'UserManagement::index', ['filter' => 'session']);
$routes->get('laporan', 'ReportController::index', ['filter' => 'session']);
$routes->get('audit', 'AuditController::index', ['filter' => 'session']);
$routes->get('riwayat-perbaikan', 'RepairController::index', ['filter' => 'session']);

// ============================================================
// API ROUTES — dilindungi global session filter
// ============================================================
$routes->group('api', ['namespace' => 'App\Controllers\API', 'filter' => 'session'], function ($routes) {

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

    // ── Centralized Report API ──
    $routes->get('reports/summary', 'ReportAPI::summary');
    $routes->get('reports/assets', 'ReportAPI::assets');

    // ── Audit Trail API ──
    $routes->get('audit', 'AuditAPI::index');

    // ── User Management API ──
    $routes->get('users', 'UserAPI::index');
    $routes->post('users', 'UserAPI::create');
    $routes->put('users/(:num)/ban', 'UserAPI::ban/$1');
    $routes->put('users/(:num)/unban', 'UserAPI::unban/$1');
    $routes->delete('users/(:num)', 'UserAPI::delete/$1');
});
