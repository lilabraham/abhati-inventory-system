<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\RepairHistoryModel;
use App\Models\AuditLogModel;
use App\Libraries\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;

class RepairAPI extends BaseController
{
    use ResponseTrait;

    protected RepairHistoryModel $repairModel;
    protected AssetModel         $assetModel;
    protected AuditLogModel      $auditLogModel;

    private const ALLOWED_FILTER_FIELDS = [
        'tanggal',
        'biaya',
        'status_akhir',
        'kondisi_akhir',
        'asset_id',
        'created_by',
        'deskripsi',
    ];

    private const ALLOWED_SORT_FIELDS = [
        'tanggal',
        'biaya',
        'status_akhir',
        'kondisi_akhir',
        'asset_id',
    ];

    public function __construct()
    {
        $this->repairModel   = new RepairHistoryModel();
        $this->assetModel    = new AssetModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function index()
    {
        if (! auth()->user()->can('repairs.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        $page    = max(1, (int) ($this->request->getVar('page') ?? 1));
        $size    = min(100, max(1, (int) ($this->request->getVar('size') ?? 15)));
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

        try {
            $query = $this->repairModel
                ->select('repair_history.*, laptop_assets.kode_aset, laptop_assets.merk, laptop_assets.model')
                ->join('laptop_assets', 'laptop_assets.id = repair_history.asset_id', 'left');

            if (! empty($filters) && is_array($filters)) {
                foreach ($filters as $filter) {
                    if (! is_array($filter)) continue;
                    $field = $filter['field'] ?? '';
                    $value = substr((string) ($filter['value'] ?? ''), 0, 200);
                    $type  = $filter['type'] ?? 'like';

                    if ($value === '') continue;
                    if (! in_array($field, self::ALLOWED_FILTER_FIELDS, true)) continue;

                    $type === 'like'
                        ? $query->like("repair_history.{$field}", $value)
                        : $query->where("repair_history.{$field}", $value);
                }
            }

            if ($search) {
                $query->groupStart()
                    ->like('repair_history.deskripsi', $search)
                    ->orLike('laptop_assets.kode_aset', $search)
                    ->orLike('laptop_assets.merk', $search)
                    ->groupEnd();
            }

            $sorted = false;
            if (! empty($sorters) && is_array($sorters)) {
                foreach ($sorters as $sorter) {
                    if (! is_array($sorter)) continue;
                    $field = $sorter['field'] ?? '';
                    $dir   = strtoupper($sorter['dir'] ?? 'DESC');

                    if (! in_array($field, self::ALLOWED_SORT_FIELDS, true)) continue;
                    if (! in_array($dir, ['ASC', 'DESC'], true)) $dir = 'DESC';

                    $query->orderBy("repair_history.{$field}", $dir);
                    $sorted = true;
                }
            }
            if (! $sorted) {
                $query->orderBy('repair_history.tanggal', 'DESC');
            }

            $logs  = $query->paginate($size, 'default', $page);
            $total = $this->repairModel->pager?->getTotal() ?? 0;
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::index() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Repair history retrieved successfully', [
            'data'      => $logs,
            'last_page' => max((int) ceil($total / $size), 1),
            'total'     => $total,
        ]), 200);
    }

    public function byAsset(int $assetId)
    {
        if (! auth()->user()->can('repairs.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $asset = $this->assetModel->find($assetId);
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::byAsset() find asset gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        if (! $asset) {
            return $this->respond(JSONResponseBuilder::make(404, false, 'Asset tidak ditemukan.'), 404);
        }

        try {
            $history = $this->repairModel
                ->where('asset_id', $assetId)
                ->orderBy('tanggal', 'DESC')
                ->findAll();
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::byAsset() find history gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Repair history retrieved successfully', [
            'asset' => $asset,
            'data'  => $history,
        ]), 200);
    }

    public function create()
    {
        if (! auth()->user()->can('repairs.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        $data  = $this->request->getJSON(true) ?? [];
        $rules = [
            'asset_id'      => 'required|integer',
            'tanggal'       => 'required|valid_date',
            'deskripsi'     => 'required',
            'biaya'         => 'permit_empty|decimal',
            'status_akhir'  => 'permit_empty|in_list[selesai,pending,gagal]',
            'kondisi_akhir' => 'permit_empty|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond(JSONResponseBuilder::make(422, false, 'Validation failed', $this->validator->getErrors()), 422);
        }

        try {
            $asset = $this->assetModel->find($data['asset_id']);
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::create() find asset gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        if (! $asset) {
            return $this->respond(JSONResponseBuilder::make(404, false, 'Asset tidak ditemukan.'), 404);
        }

        $data['created_by'] = auth()->id();

        try {
            if (! $this->repairModel->insert($data)) {
                return $this->respond(JSONResponseBuilder::make(400, false, 'Gagal menyimpan data.', $this->repairModel->errors()), 400);
            }

            $insertId = $this->repairModel->getInsertID();

            if (! empty($data['kondisi_akhir'])) {
                $this->assetModel->update($data['asset_id'], ['kondisi' => $data['kondisi_akhir']]);
            }

            $this->auditLogModel->insertLog([
                'action'      => 'CREATE',
                'module'      => 'Riwayat Perbaikan',
                'record_type' => 'repairs',
                'record_id'   => $insertId,
                'description' => "Menambah riwayat perbaikan aset: {$asset['kode_aset']} ({$asset['merk']} {$asset['model']})",
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::create() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(201, true, 'Riwayat perbaikan berhasil ditambahkan.', [
            'id' => $insertId,
        ]), 201);
    }

    public function update($id = null)
    {
        if (! auth()->user()->can('repairs.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        // try/catch 1 — pisah karena validasi harus jalan di antaranya
        try {
            $existing = $this->repairModel->find($id);
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::update() find gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        if (! $existing) {
            return $this->respond(JSONResponseBuilder::make(404, false, 'Riwayat tidak ditemukan.'), 404);
        }

        $data = $this->request->getJSON(true) ?? [];
        unset($data['asset_id'], $data['created_by'], $data['id']); // ← fix: tambah id

        $rules = [
            'tanggal'       => 'permit_empty|valid_date',
            'deskripsi'     => 'permit_empty',
            'biaya'         => 'permit_empty|decimal',
            'status_akhir'  => 'permit_empty|in_list[selesai,pending,gagal]',
            'kondisi_akhir' => 'permit_empty|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond(JSONResponseBuilder::make(422, false, 'Validation failed', $this->validator->getErrors()), 422);
        }

        // try/catch 2 — merge: find asset + update + kondisi_akhir + audit log
        try {
            $asset = $this->assetModel->find($existing['asset_id']);

            if (! $this->repairModel->update($id, $data)) {
                return $this->respond(JSONResponseBuilder::make(400, false, 'Gagal memperbarui data.', $this->repairModel->errors()), 400);
            }

            if (! empty($data['kondisi_akhir'])) {
                $this->assetModel->update($existing['asset_id'], ['kondisi' => $data['kondisi_akhir']]);
            }

            $this->auditLogModel->insertLog([
                'action'      => 'UPDATE',
                'module'      => 'Riwayat Perbaikan',
                'record_type' => 'repairs',
                'record_id'   => (int) $id,
                'description' => "Mengubah riwayat perbaikan aset: " . ($asset['kode_aset'] ?? "ID {$existing['asset_id']}"),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'RepairAPI::update() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Riwayat berhasil diperbarui.'), 200);
    }
}
