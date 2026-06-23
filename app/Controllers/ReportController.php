<?php

namespace App\Controllers;

class ReportController extends BaseController
{
    /**
     * Web Controller — hanya render UI Pusat Laporan.
     * Semua logika data ditangani oleh API\ReportAPI.
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (! (auth()->user()?->can('reports.export') ?? false)) {
            return redirect()->to('/data-aset')
                ->with('error', 'Anda tidak memiliki akses ke Pusat Laporan.');
        }

        return $this->view('reports/index');
    }
}