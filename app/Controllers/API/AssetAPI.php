<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\AuditLogModel;
use App\Models\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;
use App\Services\ImportService;
use App\Services\AssetExportService;

class AssetAPI extends BaseController
{
    use ResponseTrait;

    protected $assetModel;
    protected $auditLogModel;

    public function __construct()
    {
        parent::__construct();
        $this->assetModel = new AssetModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Get all assets for the current company (Mendukung Tabulator / Datatables)
     */
    public function index()
    {
        $responseBuilder = new JSONResponseBuilder();

        // 1. KEAMANAN B2B: Cek Company ID
        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $page = (int) ($this->request->getVar('page') ?? 1);
        $size = (int) ($this->request->getVar('size') ?? $this->request->getVar('per_page') ?? 15);
        $search = $this->request->getVar('search');

        // Wajib mengunci query dengan company_id
        $query = $this->assetModel
            ->select('laptop_assets.*, (SELECT COUNT(*) FROM repair_history WHERE repair_history.asset_id = laptop_assets.id) as total_perbaikan')
            ->where('laptop_assets.company_id', $this->userCompanyId);

        if ($search) {
            $query->groupStart()
                ->like('laptop_assets.kode_aset', $search)
                ->orLike('laptop_assets.merk', $search)
                ->orLike('laptop_assets.model', $search)
                ->groupEnd();
        }

        // Asumsi method withRepairCount() sudah kamu sesuaikan di Model
        $assets = $query->paginate($size, 'default', $page);
        $total = $this->assetModel->pager->getTotal();

        $responseData = [
            'data'      => $assets,
            'last_page' => ceil($total / $size),
            'total'     => $total
        ];

        // 2. STANDAR RESPONSE ABHATI
        $responseBuilder->buildResponse(200, true, 'Assets retrieved successfully', $responseData);
        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Get single asset detail
     */
    public function show($id = null)
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        // Pastikan hanya bisa melihat aset milik perusahaannya sendiri
        $asset = $this->assetModel
            ->where('company_id', $this->userCompanyId)
            ->find($id);

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
    public function create() // Berubah dari store() menjadi create() menyesuaikan konvensi CI4
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
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

        // Sisipkan company_id dan created_by sebelum disimpan
        $data['company_id'] = $this->userCompanyId;
        $data['created_by'] = auth()->id();

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

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        // Verifikasi kepemilikan data sebelum diupdate
        $existingAsset = $this->assetModel->where('company_id', $this->userCompanyId)->find($id);
        if (!$existingAsset) {
            $responseBuilder->buildResponse(404, false, 'Asset not found');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $data = $this->request->getJSON(true);
        // Validasi is_unique harus mengecualikan ID saat ini
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
    public function delete($id = null) // Berubah dari destroy menjadi delete (bawaan Restful CI4)
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        // Verifikasi kepemilikan
        $existingAsset = $this->assetModel->where('company_id', $this->userCompanyId)->find($id);
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

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            $responseBuilder->buildResponse(400, false, 'Invalid or missing file.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        try {
            $service = new ImportService($this->assetModel, $this->auditLogModel);

            // lemparkan company_id ke service agar data yang diimport masuk ke perusahaan yang benar!
            $result = $service->importFromFile($file, $this->userCompanyId);
            $status = $result['failed'] === 0 ? 200 : 207;

            $responseBuilder->buildResponse($status, true, "{$result['imported']} data imported.", $result);
            return $this->respond($responseBuilder, $responseBuilder->code);
        } catch (\Exception $e) {
            $responseBuilder->buildResponse(422, false, $e->getMessage());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }
    }

    /*
     * Export Excel
     */
    public function exportExcel()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        try {
        } catch (\Exception $e) {
            $responseBuilder->buildResponse(500, false, 'Failed to export data: ' . $e->getMessage());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }
    }
}
