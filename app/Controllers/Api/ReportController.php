<?php
// File: app/Controllers/Api/ReportController.php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AuditLogModel;          // ← TAMBAH INI
use App\Services\ReportService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ReportController extends BaseController
{
    protected ReportService $reportService;
    protected AssetModel $assetModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        // Injeksi dependensi (Enterprise Standard)
        $db                  = \Config\Database::connect();
        $this->reportService = new ReportService($db);
        $this->assetModel    = new AssetModel();
    }

    public function summary(): ResponseInterface
    {
        // Hitung total aset di level model untuk menjaga service tetap independen
        $totalAset = $this->assetModel->where('deleted_at IS NULL')->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $this->reportService->getSummary($totalAset),
        ]);
    }

    public function assets(): ResponseInterface
    {
        // Tetap menggunakan logika native CI4 Pager dari kode aslimu (Lebih optimal)
        $limit  = min((int) ($this->request->getGet('limit') ?? 500), 500);
        $page   = max((int) ($this->request->getGet('page') ?? 1), 1);

        $assets = $this->assetModel->withRepairCount($limit, $page);
        $pager  = $this->assetModel->pager;

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

    public function exportExcel(): void
{
    try {
        $assets      = $this->reportService->getAllAssets();
        $spreadsheet = $this->reportService->buildAssetSpreadsheet($assets);
        $filename    = 'Laporan_Aset_' . date('Ymd_His') . '.xlsx';

        model(AuditLogModel::class)->insertLog([
            'action'      => 'EXPORT',
            'module'      => 'Pusat Laporan',
            'record_type' => 'assets',
            'description' => 'Export Excel ' . count($assets) . ' aset.',
            'status'      => 'success',
        ]);

        $this->reportService->streamExcel($spreadsheet, $filename);

    } catch (\Throwable $e) {
        model(AuditLogModel::class)->insertLog([
            'action'      => 'EXPORT',
            'module'      => 'Pusat Laporan',
            'description' => 'Export gagal: ' . $e->getMessage(),
            'status'      => 'failed',
        ]);
        throw $e;
    }
}
}