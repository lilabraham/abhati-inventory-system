<?php
// File: app/Controllers/api/RepairController.php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\RepairHistoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class RepairController extends BaseController
{
    private RepairHistoryModel $model;

    public function __construct()
    {
        $this->model = new RepairHistoryModel();
    }

    public function byAsset(int $assetId): ResponseInterface
    {
        $asset = (new AssetModel())->find($assetId);

        if (!$asset) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'asset'  => $asset,
            'data'   => $this->model->getByAsset($assetId),
        ]);
    }

    public function store(): ResponseInterface
    {
        $data  = $this->request->getJSON(true);
        $rules = [
            'asset_id'  => 'required|integer|is_not_unique[laptop_assets.id]',
            'tanggal'   => 'required|valid_date',
            'deskripsi' => 'required',
            'biaya'     => 'permit_empty|decimal',
            'status_akhir' => 'permit_empty|in_list[selesai,pending,gagal]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $id = $this->model->insert($data);

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['status' => 'success', 'id' => $id, 'message' => 'Riwayat perbaikan berhasil ditambahkan.']);
    }

    public function update(int $id): ResponseInterface
    {
        if (!$this->model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Riwayat tidak ditemukan.']);
        }

        $data = $this->request->getJSON(true);
        $this->model->update($id, $data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Riwayat berhasil diperbarui.',
        ]);
    }

    public function destroy(int $id): ResponseInterface
    {
        if (!$this->model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Riwayat tidak ditemukan.']);
        }

        $this->model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Riwayat berhasil dihapus.',
        ]);
    }
}