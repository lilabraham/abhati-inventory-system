<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\RepairHistoryModel;
use App\Libraries\JSONResponseBuilder;
use App\Services\ReportService;
use CodeIgniter\API\ResponseTrait;

class ReportAPI extends BaseController
{
    use ResponseTrait;

    protected ReportService $reportService;
    protected AssetModel $assetModel;

    private const ALLOWED_FIELDS = [
        'kode_aset',
        'merk',
        'model',
        'serial_number',
        'pengguna',
        'lokasi',
        'kondisi',
        'created_at',
    ];

    public function __construct()
    {
        $this->assetModel    = new AssetModel();
        $this->reportService = new ReportService($this->assetModel, new RepairHistoryModel());
    }

    public function summary(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! auth()->user()->can('assets.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Anda tidak memiliki akses.'), 403);
        }

        try {
            $data = $this->reportService->getSummary();
        } catch (\Throwable $e) {
            log_message('error', 'Gagal mengambil summary report: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Summary retrieved successfully', $data), 200);
    }

    public function assets(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! auth()->user()->can('reports.export')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Anda tidak memiliki akses ke laporan.'), 403);
        }

        $page    = max((int) ($this->request->getVar('page') ?? 1), 1);
        $size    = max(min((int) ($this->request->getVar('size') ?? 15), 500), 1); // max 500 (untuk export semua data sekaligus)
        $search  = $this->request->getVar('search');
        $search  = $search ? substr(trim($search), 0, 100) : null;
        $sorters = $this->request->getVar('sort') ?? $this->request->getVar('sorters');
        $filters = $this->request->getVar('filter') ?? $this->request->getVar('filters');

        if (is_string($sorters)) {
            $sorters = json_decode($sorters, true);
            if (json_last_error() !== JSON_ERROR_NONE) $sorters = null;
        }
        if (is_string($filters)) {
            $filters = json_decode($filters, true);
            if (json_last_error() !== JSON_ERROR_NONE) $filters = null;
        }

        if (! empty($filters) && is_array($filters)) {
            foreach ($filters as $filter) {
                if (! is_array($filter)) continue;
                $field = $filter['field'] ?? '';
                $value = substr((string) ($filter['value'] ?? ''), 0, 200);
                $type  = $filter['type'] ?? 'like';

                if ($value === '') continue;
                if (! in_array($field, self::ALLOWED_FIELDS, true)) continue;

                $type === 'like'
                    ? $this->assetModel->like($field, $value)
                    : $this->assetModel->where($field, $value);
            }
        }

        if ($search) {
            $this->assetModel->groupStart()
                ->like('kode_aset', $search)
                ->orLike('merk', $search)
                ->orLike('model', $search)
                ->groupEnd();
        }

        $sorted = false;
        if (! empty($sorters) && is_array($sorters)) {
            foreach ($sorters as $sorter) {
                if (! is_array($sorter)) continue;    // ← guard
                $field = $sorter['field'] ?? '';
                $dir   = strtoupper($sorter['dir'] ?? 'DESC');

                if (! in_array($field, self::ALLOWED_FIELDS, true)) continue;
                if (! in_array($dir, ['ASC', 'DESC'], true)) $dir = 'DESC';

                $this->assetModel->orderBy($field, $dir);
                $sorted = true;
            }
        }
        if (! $sorted) {
            $this->assetModel->orderBy('created_at', 'DESC');
        }

        try {
            $assets = $this->assetModel->paginate($size, 'default', $page);
            $total  = $this->assetModel->pager?->getTotal() ?? 0;
        } catch (\Throwable $e) {
            log_message('error', 'Gagal mengambil report assets: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Assets retrieved successfully', [
            'data'      => $assets,
            'last_page' => max((int) ceil($total / $size), 1),
            'total'     => $total,
        ]), 200);
    }
}
