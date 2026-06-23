<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;
use App\Services\ImportService;

class AssetAPI extends BaseController
{
    use ResponseTrait;

    protected $assetModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
    }

    /**
     * Get all assets (Mendukung Tabulator / Datatables)
     */
    public function index()
    {
        $responseBuilder = new JSONResponseBuilder();

        $page = (int) ($this->request->getVar('page') ?? 1);
        $size = (int) ($this->request->getVar('size') ?? $this->request->getVar('per_page') ?? 15);
        $search = $this->request->getVar('search');

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
        $total = $this->assetModel->pager->getTotal();

        $responseData = [
            'data'      => $assets,
            'last_page' => ceil($total / $size),
            'total'     => $total
        ];

        $responseBuilder->buildResponse(200, true, 'Assets retrieved successfully', $responseData);
        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Get single asset detail
     */
    public function show($id = null)
    {
        $responseBuilder = new JSONResponseBuilder();

        $asset = $this->assetModel->find($id);

        if ($asset) {
            $responseBuilder->buildResponse(200, true, 'Asset retrieved successfully', $asset);
        } else {
            $responseBuilder->buildResponse(404, false, 'Asset not found');
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Create new asset
     */
    public function create()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->can('assets.manage')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }

        $data = $this->request->getJSON(true);
        $rules = [
            'kode_aset' => 'required|is_unique[laptop_assets.kode_aset]',
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validate($rules, $data)) {
            $responseBuilder->buildResponse(400, false, 'Validation failed', $this->validator->getErrors());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        if ($this->assetModel->insert($data)) {
            $responseBuilder->buildResponse(201, true, 'Asset created successfully', ['id' => $this->assetModel->getInsertID()]);
        } else {
            $responseBuilder->buildResponse(400, false, 'Failed to create asset', $this->assetModel->errors());
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Update existing asset
     */
    public function update($id = null)
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->can('assets.manage')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }

        $existingAsset = $this->assetModel->find($id);
        if (!$existingAsset) {
            $responseBuilder->buildResponse(404, false, 'Asset not found');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $data = $this->request->getJSON(true);
        $rules = [
            'kode_aset' => "required|max_length[20]|is_unique[laptop_assets.kode_aset,id,{$id}]",
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validate($rules, $data)) {
            $responseBuilder->buildResponse(400, false, 'Validation failed', $this->validator->getErrors());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        if ($this->assetModel->update($id, $data)) {
            $responseBuilder->buildResponse(200, true, 'Asset updated successfully');
        } else {
            $responseBuilder->buildResponse(400, false, 'Failed to update asset', $this->assetModel->errors());
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Delete asset
     */
    public function delete($id = null)
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->can('assets.manage')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }

        $existingAsset = $this->assetModel->find($id);
        if (!$existingAsset) {
            $responseBuilder->buildResponse(404, false, 'Asset not found');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        if ($this->assetModel->delete($id)) {
            $responseBuilder->buildResponse(200, true, 'Asset deleted successfully');
        } else {
            $responseBuilder->buildResponse(400, false, 'Failed to delete asset');
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Import Excel
     */
    public function importExcel()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->can('imports.run')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }

        $rows = $this->request->getJSON(true)['rows'] ?? [];

        if (empty($rows)) {
            $responseBuilder->buildResponse(400, false, 'Data rows kosong atau tidak ditemukan.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        try {
            $service = new ImportService($this->assetModel);
            $result  = $service->processRows($rows);
            $status  = $result['failed'] === 0 ? 200 : 207;

            $responseBuilder->buildResponse($status, true, "{$result['imported']} data imported.", $result);
            return $this->respond($responseBuilder, $responseBuilder->code);
        } catch (\RuntimeException $e) {
            $responseBuilder->buildResponse(422, false, $e->getMessage());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }
    }
}
