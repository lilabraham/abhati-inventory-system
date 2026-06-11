<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\RepairHistoryModel;

class AssetController extends BaseController
{
    private AssetModel $model;

    public function __construct()
    {
        $this->model = new AssetModel();
    }

    public function index(): string
    {
        return view('assets/index', [
            'title' => 'Aset Laptop',
        ]);
    }

    public function show(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $asset = $this->model->find($id);

        if (!$asset) {
            return redirect()->to('/assets')->with('error', 'Asset tidak ditemukan.');
        }

        return view('assets/show', [
            'title'   => 'Detail Aset: ' . $asset['kode_aset'],
            'asset'   => $asset,
            'repairs' => (new RepairHistoryModel())->getByAsset($id),
        ]);
    }

    public function report(): string
    {
        return view('reports/index', [
            'title' => 'Laporan Aset Laptop',
        ]);
    }
}