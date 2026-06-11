<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReportController extends BaseController
{
    public function summary(): ResponseInterface
    {
        $model = new AssetModel();
        $db    = \Config\Database::connect();

        $kondisiStats = $db->table('laptop_assets')
            ->select('kondisi, COUNT(*) as total')
            ->where('deleted_at', null)
            ->groupBy('kondisi')
            ->get()
            ->getResultArray();

        $totalBiaya = $db->table('repair_history')
            ->selectSum('biaya')
            ->get()
            ->getRow()->biaya ?? 0;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'assets'                 => $model->withRepairCount(),
                'kondisi_stats'          => $kondisiStats,
                'total_biaya_perbaikan'  => (float) $totalBiaya,
                'total_aset'             => $model->countAllResults(),
                'generated_at'           => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}