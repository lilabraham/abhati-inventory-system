<?php
// File: app/Controllers/ReportController.php
// Pastikan letaknya di luar folder Api!
namespace App\Controllers;

class ReportController extends BaseController
{
    /**
     * Web Controller ini murni HANYA untuk menampilkan UI Halaman Pusat Laporan.
     * Semua logika data JSON dan Excel sudah ditangani oleh App\Controllers\Api\ReportController
     */
    public function index(): string
    {
        return view('reports/index');
    }
}