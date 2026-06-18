<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AuditLogModel;
use App\Models\JSONResponseBuilder;
use App\Services\ReportService;
use CodeIgniter\API\ResponseTrait;

class ReportAPI extends BaseController
{
    use ResponseTrait;

    protected $reportService;
    protected $assetModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->reportService = new ReportService();
        $this->assetModel    = new AssetModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Summary report for current company
     */
    public function summary()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $totalAset = $this->assetModel
            ->where('company_id', $this->userCompanyId)
            ->countAllResults();

        $data = $this->reportService->getSummary($totalAset, $this->userCompanyId);

        $responseBuilder->buildResponse(200, true, 'Summary retrieved successfully', $data);
        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Paginated asset list for report (Tabulator-ready)
     */
    public function assets()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $page    = max((int) ($this->request->getVar('page') ?? 1), 1);
        $size    = min((int) ($this->request->getVar('size') ?? 15), 500);
        $search  = $this->request->getVar('search');

        $sorters = $this->request->getVar('sort') ?? $this->request->getVar('sorters');
        $filters = $this->request->getVar('filter') ?? $this->request->getVar('filters');

        if (is_string($sorters)) $sorters = json_decode($sorters, true);
        if (is_string($filters)) $filters = json_decode($filters, true);

        $query = $this->assetModel->where('company_id', $this->userCompanyId);

        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $filter) {
                $field = $filter['field'] ?? '';
                $value = $filter['value'] ?? '';
                $type  = $filter['type'] ?? 'like';

                if ($value === '' || $value === null) continue;

                $type === 'like'
                    ? $query->like($field, $value)
                    : $query->where($field, $value);
            }
        }

        if ($search) {
            $query->groupStart()
                ->like('kode_aset', $search)
                ->orLike('merk', $search)
                ->orLike('model', $search)
            ->groupEnd();
        }

        if (!empty($sorters) && is_array($sorters)) {
            foreach ($sorters as $sorter) {
                $query->orderBy($sorter['field'], strtoupper($sorter['dir']));
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $assets = $query->paginate($size, 'default', $page);
        $total  = $this->assetModel->pager->getTotal();

        $responseBuilder->buildResponse(200, true, 'Assets retrieved successfully', [
            'data'      => $assets,
            'last_page' => ceil($total / $size),
            'total'     => $total,
        ]);

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Export Excel — stream langsung, bukan JSON response
     * Error dikembalikan sebagai JSON
     */
    public function exportExcel()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        try {
            $assets      = $this->reportService->getAllAssets($this->userCompanyId);
            $spreadsheet = $this->reportService->buildAssetSpreadsheet($assets);
            $filename    = 'Laporan_Aset_' . date('Ymd_His') . '.xlsx';

            $this->auditLogModel->insertLog([
                'company_id'  => $this->userCompanyId,
                'user_id'     => auth()->id(),
                'action'      => 'EXPORT',
                'module'      => 'Pusat Laporan',
                'record_type' => 'assets',
                'description' => 'Export Excel ' . count($assets) . ' aset.',
                'status'      => 'success',
            ]);

            // Stream Excel — intentionally tidak pakai JSONResponseBuilder
            $this->reportService->streamExcel($spreadsheet, $filename);

        } catch (\Throwable $e) {
            $this->auditLogModel->insertLog([
                'company_id'  => $this->userCompanyId,
                'user_id'     => auth()->id(),
                'action'      => 'EXPORT',
                'module'      => 'Pusat Laporan',
                'record_type' => 'assets',
                'description' => 'Export gagal: ' . $e->getMessage(),
                'status'      => 'failed',
            ]);

            $responseBuilder->buildResponse(500, false, 'Export gagal: ' . $e->getMessage());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }
    }
}