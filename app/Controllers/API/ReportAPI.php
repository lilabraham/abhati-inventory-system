<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AuditLogModel;
use App\Models\RepairHistoryModel;
use App\Libraries\JSONResponseBuilder;
use App\Services\ReportService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class ReportAPI extends BaseController
{
    use ResponseTrait;

    protected ReportService   $reportService;
    protected AssetModel      $assetModel;
    protected AuditLogModel   $auditLogModel;

    // Max size khusus export — exception terdokumentasi dari aturan clamp 100
    private const EXPORT_SIZE_MAX = 500;

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
        $this->auditLogModel = new AuditLogModel();
        $this->reportService = new ReportService($this->assetModel, new RepairHistoryModel());
    }

    public function summary(): ResponseInterface
    {
        if (! auth()->user()->can('assets.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $data = $this->reportService->getSummary();
        } catch (\Throwable $e) {
            log_message('error', '[ReportAPI::summary] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'OK', $data));
    }

    public function assets(): ResponseInterface
    {
        if (! auth()->user()->can('reports.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $page    = max(1, min(500, (int) ($this->request->getGet('page') ?? 1)));
            $size    = max(1, min(self::EXPORT_SIZE_MAX, (int) ($this->request->getGet('size') ?? 15)));
            $search  = $this->request->getGet('search');
            $search  = $search ? substr(trim($search), 0, 100) : null;
            $sorters = $this->request->getGet('sort') ?? $this->request->getGet('sorters');
            $filters = $this->request->getGet('filter') ?? $this->request->getGet('filters');

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
                    if (! is_array($sorter)) continue;
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

            $assets = $this->assetModel->paginate($size, 'default', $page);
            $total  = $this->assetModel->pager?->getTotal() ?? 0;

            // Audit log — hanya tulis saat page 1 (satu log per sesi export)
            if ($page === 1) {
                $this->auditLogModel->insertLog([
                    'action'      => 'EXPORT',
                    'record_type' => 'assets',
                    'module'      => 'Pusat Laporan',
                    'record_id'   => null,
                    'description' => 'Mengekspor data aset laptop ke dokumen laporan.',
                ]);
            }

            return $this->respond(JSONResponseBuilder::make(200, true, 'OK', [
                'data'      => $assets,
                'last_page' => max((int) ceil($total / $size), 1),
                'total'     => $total,
            ]));
        } catch (\Throwable $e) {
            log_message('error', '[ReportAPI::assets] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }
    }
}
