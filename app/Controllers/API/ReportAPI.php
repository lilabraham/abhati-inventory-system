<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\JSONResponseBuilder;
use App\Services\ReportService;
use CodeIgniter\API\ResponseTrait;

class ReportAPI extends BaseController
{
    use ResponseTrait;

    protected ReportService $reportService;
    protected AssetModel $assetModel;

    private const ALLOWED_FIELDS = [
        'kode_aset', 'merk', 'model', 'serial_number',
        'pengguna', 'lokasi', 'kondisi', 'created_at',
    ];

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->assetModel    = new AssetModel();
    }

    // FIX #1: guard assets.view — summary dipakai stat cards di halaman Aset (semua role)
    public function summary(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! (auth()->user()?->can('assets.view') ?? false)) {
            return $this->failForbidden('Anda tidak memiliki akses.');
        }

        $responseBuilder = new JSONResponseBuilder();
        $totalAset       = $this->assetModel->countAllResults();
        $data            = $this->reportService->getSummary($totalAset);

        $responseBuilder->buildResponse(200, true, 'Summary retrieved successfully', $data);
        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    public function assets(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (! (auth()->user()?->can('reports.export') ?? false)) {
            return $this->failForbidden('Anda tidak memiliki akses ke laporan.');
        }

        $responseBuilder = new JSONResponseBuilder();

        $page = max((int) ($this->request->getVar('page') ?? 1), 1);
        // FIX #2: max 1 untuk hindari division by zero
        $size   = max(min((int) ($this->request->getVar('size') ?? 15), 500), 1);
        $search = $this->request->getVar('search');

        $sorters = $this->request->getVar('sort') ?? $this->request->getVar('sorters');
        $filters = $this->request->getVar('filter') ?? $this->request->getVar('filters');

        // FIX #3: decode + validasi JSON, bail jika malformed
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
                $field = $filter['field'] ?? '';
                $value = $filter['value'] ?? '';
                $type  = $filter['type'] ?? 'like';

                if ($value === '' || $value === null) continue;
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

        if (! empty($sorters) && is_array($sorters)) {
            foreach ($sorters as $sorter) {
                $field = $sorter['field'] ?? '';
                $dir   = strtoupper($sorter['dir'] ?? 'DESC');

                if (! in_array($field, self::ALLOWED_FIELDS, true)) continue;
                if (! in_array($dir, ['ASC', 'DESC'], true)) $dir = 'DESC';

                $this->assetModel->orderBy($field, $dir);
            }
        } else {
            $this->assetModel->orderBy('created_at', 'DESC');
        }

        $assets = $this->assetModel->paginate($size, 'default', $page);
        // FIX #4: null-safe pager access
        $total  = $this->assetModel->pager?->getTotal() ?? 0;

        $responseBuilder->buildResponse(200, true, 'Assets retrieved successfully', [
            'data'      => $assets,
            'last_page' => max((int) ceil($total / $size), 1),
            'total'     => $total,
        ]);

        return $this->respond($responseBuilder, $responseBuilder->code);
    }
}