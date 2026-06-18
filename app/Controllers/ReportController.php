<?php
// File: app/Controllers/ReportController.php
namespace App\Controllers;

class ReportController extends BaseController
{
    /**
     * Web Controller ini murni HANYA untuk menampilkan UI Halaman Pusat Laporan.
     * Semua logika data JSON dan Excel ditangani oleh App\Controllers\API\ReportAPI
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->view('reports/index');
    }
}