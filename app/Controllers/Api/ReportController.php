<?php
// File: app/Controllers/Api/ReportController.php
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
                'kondisi_stats'         => $kondisiStats,
                'total_biaya_perbaikan' => (float) $totalBiaya,
                'total_aset'            => $model->countAllResults(),
                'generated_at'          => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function assets(): ResponseInterface
    {
        $model  = new AssetModel();
        $limit  = min((int) ($this->request->getGet('limit') ?? 500), 500);
        $page   = (int) ($this->request->getGet('page') ?? 1);

        $assets = $model->withRepairCount($limit, $page);
        $pager  = $model->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $assets,
            'pager'  => [
                'current_page' => $pager->getCurrentPage(),
                'total'        => $pager->getTotal(),
                'total_pages'  => $pager->getPageCount(),
            ],
        ]);
    }
}