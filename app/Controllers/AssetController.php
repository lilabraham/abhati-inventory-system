<?php

namespace App\Controllers;

class AssetController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return $this->view('assets/index', [
            'title' => 'Aset Laptop',
        ]);
    }

    public function show(int $id): string
    {
        return $this->view('assets/show', [
            'title'    => 'Detail Aset',
            'asset_id' => $id,
        ]);
    }

    public function report(): string
    {
        return $this->view('reports/index', [
            'title' => 'Laporan Aset Laptop',
        ]);
    }
}