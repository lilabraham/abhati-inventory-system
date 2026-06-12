<?php
// File: app/Controllers/api/AssetController.php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use CodeIgniter\HTTP\ResponseInterface;

class AssetController extends BaseController
{
    private AssetModel $model;

    public function __construct()
    {
        $this->model = new AssetModel();
    }

    public function index(): ResponseInterface
{
    $page    = (int) ($this->request->getGet('page') ?? 1);
    $perPage = (int) ($this->request->getGet('per_page') ?? 15);

    $assets = $this->model->withRepairCount($perPage, $page);
    $pager  = $this->model->pager;

    return $this->response->setJSON([
        'status' => 'success',
        'data'   => $assets,
        'pager'  => [
            'current_page' => $pager->getCurrentPage(),
            'per_page'     => $perPage,
            'total'        => $pager->getTotal(),
            'total_pages'  => $pager->getPageCount(),
            'has_previous' => $page > 1,
            'has_next'     => $page < $pager->getPageCount(),
        ],
    ]);
}

    public function show(int $id): ResponseInterface
    {
        $asset = $this->model->find($id);

        if (!$asset) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $asset,
        ]);
    }

    public function store(): ResponseInterface
    {
        $data  = $this->request->getJSON(true);
        $rules = [
            'kode_aset' => 'required|max_length[20]|is_unique[laptop_assets.kode_aset]',
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $id = $this->model->insert($data);

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['status' => 'success', 'id' => $id, 'message' => 'Asset berhasil ditambahkan.']);
    }

    public function update(int $id): ResponseInterface
    {
        if (!$this->model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        $data  = $this->request->getJSON(true);
        $rules = [
            'kode_aset' => "required|max_length[20]|is_unique[laptop_assets.kode_aset,id,{$id}]",
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $this->model->update($id, $data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Asset berhasil diperbarui.',
        ]);
    }

    public function destroy(int $id): ResponseInterface
    {
        if (!$this->model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        $this->model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Asset berhasil dihapus.',
        ]);
    }
}