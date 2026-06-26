<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AuditLogModel;
use App\Libraries\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;
use App\Services\ImportService;

class AssetAPI extends BaseController
{
    use ResponseTrait;

    protected AssetModel    $assetModel;
    protected AuditLogModel $auditLogModel;
    protected ImportService $importService;

    public function __construct()
    {
        $this->assetModel    = new AssetModel();
        $this->auditLogModel = new AuditLogModel();
        $this->importService = new ImportService($this->assetModel);
    }

    public function index()
    {
        if (! auth()->user()->can('assets.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        $page   = max(1, (int) ($this->request->getVar('page') ?? 1));
        $size   = min(100, max(1, (int) ($this->request->getVar('size') ?? $this->request->getVar('per_page') ?? 15)));
        $search = $this->request->getVar('search');
        $search = $search ? substr(trim($search), 0, 100) : null;

        try {
            $query = $this->assetModel
                ->select('laptop_assets.*, (SELECT COUNT(*) FROM repair_history WHERE repair_history.asset_id = laptop_assets.id) as total_perbaikan');

            if ($search) {
                $query->groupStart()
                    ->like('laptop_assets.kode_aset', $search)
                    ->orLike('laptop_assets.merk', $search)
                    ->orLike('laptop_assets.model', $search)
                    ->groupEnd();
            }

            $assets = $query->paginate($size, 'default', $page);
            $total  = $this->assetModel->pager?->getTotal() ?? 0;
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::index() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Assets retrieved successfully', [
            'data'      => $assets,
            'last_page' => max((int) ceil($total / $size), 1),
            'total'     => $total,
        ]), 200);
    }

    public function show($id = null)
    {
        if (! auth()->user()->can('assets.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $asset = $this->assetModel->find($id);
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::show() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        if (! $asset) {
            return $this->respond(JSONResponseBuilder::make(404, false, 'Asset not found'), 404);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Asset retrieved successfully', $asset), 200);
    }

    public function create()
    {
        if (! auth()->user()->can('assets.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        $data = $this->request->getJSON(true) ?? [];
        unset($data['created_by'], $data['id']);

        $rules = [
            'kode_aset' => 'required|max_length[20]|is_unique[laptop_assets.kode_aset]',
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond(JSONResponseBuilder::make(400, false, 'Validation failed', $this->validator->getErrors()), 400);
        }

        if ($this->assetModel->withDeleted()->where('kode_aset', $data['kode_aset'])->countAllResults() > 0) {
            return $this->respond(JSONResponseBuilder::make(400, false, 'Validation failed', [
                'kode_aset' => 'Kode aset ini sudah pernah digunakan sebelumnya.',
            ]), 400);
        }

        try {
            if (! $this->assetModel->insert($data)) {
                return $this->respond(JSONResponseBuilder::make(400, false, 'Failed to create asset', $this->assetModel->errors()), 400);
            }

            $insertId = $this->assetModel->getInsertID();

            $this->auditLogModel->insertLog([
                'action'      => 'CREATE',
                'module'      => 'Asset Laptop',
                'record_type' => 'assets',
                'record_id'   => $insertId,
                'description' => "Menambah aset baru: {$data['kode_aset']} ({$data['merk']} {$data['model']})",
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::create() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(201, true, 'Asset created successfully', ['id' => $insertId]), 201);
    }

    public function update($id = null)
    {
        if (! auth()->user()->can('assets.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $existing = $this->assetModel->find($id);
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::update() find gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        if (! $existing) {
            return $this->respond(JSONResponseBuilder::make(404, false, 'Asset not found'), 404);
        }

        $data = $this->request->getJSON(true) ?? [];
        unset($data['created_by'], $data['id']);

        $rules = [
            'kode_aset' => "required|max_length[20]|is_unique[laptop_assets.kode_aset,id,{$id}]",
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond(JSONResponseBuilder::make(400, false, 'Validation failed', $this->validator->getErrors()), 400);
        }

        $dupeExists = $this->assetModel->withDeleted()
            ->where('kode_aset', $data['kode_aset'])
            ->where('id !=', $id)
            ->countAllResults() > 0;

        if ($dupeExists) {
            return $this->respond(JSONResponseBuilder::make(400, false, 'Validation failed', [
                'kode_aset' => 'Kode aset ini sudah pernah digunakan sebelumnya.',
            ]), 400);
        }

        try {
            if (! $this->assetModel->update($id, $data)) {
                return $this->respond(JSONResponseBuilder::make(400, false, 'Failed to update asset', $this->assetModel->errors()), 400);
            }

            $this->auditLogModel->insertLog([
                'action'      => 'UPDATE',
                'module'      => 'Asset Laptop',
                'record_type' => 'assets',
                'record_id'   => (int) $id,
                'description' => "Mengubah data aset: {$data['kode_aset']} ({$data['merk']} {$data['model']})",
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::update() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Asset updated successfully'), 200);
    }

    public function delete($id = null)
    {
        if (! auth()->user()->can('assets.manage')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $existing = $this->assetModel->find($id);
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::delete() find gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        if (! $existing) {
            return $this->respond(JSONResponseBuilder::make(404, false, 'Asset not found'), 404);
        }

        try {
            if (! $this->assetModel->delete($id)) {
                return $this->respond(JSONResponseBuilder::make(400, false, 'Failed to delete asset'), 400);
            }

            $this->auditLogModel->insertLog([
                'action'      => 'DELETE',
                'module'      => 'Asset Laptop',
                'record_type' => 'assets',
                'record_id'   => (int) $id,
                'description' => "Menghapus aset: {$existing['kode_aset']} ({$existing['merk']} {$existing['model']})",
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'AssetAPI::delete() gagal: ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(200, true, 'Asset deleted successfully'), 200);
    }

    public function importExcel()
    {
        if (! auth()->user()->can('imports.run')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        try {
            $body        = $this->request->getJSON(true) ?? [];
            $rows        = $body['rows']                 ?? [];
            $isLast      = (bool) ($body['is_last_chunk']        ?? false);
            $grandTotal  = (int)  ($body['grand_total_imported'] ?? 0);
            $grandFailed = (int)  ($body['grand_total_failed']   ?? 0);

            if (! is_array($rows)) {
                return $this->respond(JSONResponseBuilder::make(400, false, 'Format data tidak valid.'), 400);
            }

            $result = $this->importService->processRows($rows);

            // Tulis 1 log hanya di chunk terakhir, pakai grand total akumulasi dari client
            if ($isLast) {
                $finalImported = $grandTotal + ($result['imported'] ?? 0);
                $finalFailed   = $grandFailed + ($result['failed']  ?? 0);
                $this->auditLogModel->insertLog([
                    'action'      => 'IMPORT',
                    'target_type' => 'asset',
                    'description' => "Import Excel: {$finalImported} berhasil, {$finalFailed} gagal.",
                ]);
            }

            $status = 200;
            return $this->respond(JSONResponseBuilder::make($status, true, "{$result['imported']} data imported.", $result), $status);
        } catch (\Throwable $e) {
            log_message('error', '[AssetAPI::importExcel] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal saat memproses import.'), 500);
        }
    }
}
