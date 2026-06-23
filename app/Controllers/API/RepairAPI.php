<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use App\Models\RepairHistoryModel;
use App\Models\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;

class RepairAPI extends BaseController
{
    use ResponseTrait;

    protected $repairModel;
    protected $assetModel;

    public function __construct()
    {
        $this->repairModel = new RepairHistoryModel();
        $this->assetModel  = new AssetModel();
    }

    /**
     * Get all repair history (Tabulator-ready)
     */
    public function index()
    {
        $responseBuilder = new JSONResponseBuilder();

        $page    = (int) ($this->request->getVar('page') ?? 1);
        $size    = (int) ($this->request->getVar('size') ?? 15);
        $search  = $this->request->getVar('search');

        $sorters = $this->request->getVar('sort') ?? $this->request->getVar('sorters');
        $filters = $this->request->getVar('filter') ?? $this->request->getVar('filters');

        if (is_string($sorters)) $sorters = json_decode($sorters, true);
        if (is_string($filters)) $filters = json_decode($filters, true);

        $query = $this->repairModel
            ->select('repair_history.*, laptop_assets.kode_aset, laptop_assets.merk, laptop_assets.model')
            ->join('laptop_assets', 'laptop_assets.id = repair_history.asset_id', 'left');

        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $filter) {
                $field = $filter['field'] ?? '';
                $value = $filter['value'] ?? '';
                $type  = $filter['type'] ?? 'like';

                if ($value === '' || $value === null) continue;

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

        if (!empty($sorters) && is_array($sorters)) {
            foreach ($sorters as $sorter) {
                $query->orderBy("repair_history.{$sorter['field']}", strtoupper($sorter['dir']));
            }
        } else {
            $query->orderBy('repair_history.tanggal', 'DESC');
        }

        $logs  = $query->paginate($size, 'default', $page);
        $total = $this->repairModel->pager->getTotal();

        $responseBuilder->buildResponse(200, true, 'Repair history retrieved successfully', [
            'data'      => $logs,
            'last_page' => ceil($total / $size),
            'total'     => $total,
        ]);

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Get repair history by asset
     */
    public function byAsset(int $assetId)
    {
        $responseBuilder = new JSONResponseBuilder();

        $asset = $this->assetModel->find($assetId);

        if (!$asset) {
            $responseBuilder->buildResponse(404, false, 'Asset tidak ditemukan.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $history = $this->repairModel
            ->where('asset_id', $assetId)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $responseBuilder->buildResponse(200, true, 'Repair history retrieved successfully', [
            'asset' => $asset,
            'data'  => $history,
        ]);

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Create repair record
     */
    public function create()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->can('repairs.manage')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }
        $data  = $this->request->getJSON(true);
        $rules = [
            'asset_id'      => 'required|integer',
            'tanggal'       => 'required|valid_date',
            'deskripsi'     => 'required',
            'biaya'         => 'permit_empty|decimal',
            'status_akhir'  => 'permit_empty|in_list[selesai,pending,gagal]',
            'kondisi_akhir' => 'permit_empty|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validate($rules, $data)) {
            $responseBuilder->buildResponse(422, false, 'Validation failed', $this->validator->getErrors());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $asset = $this->assetModel->find($data['asset_id']);
        if (!$asset) {
            $responseBuilder->buildResponse(404, false, 'Asset tidak ditemukan.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $data['created_by'] = auth()->id();

        if ($this->repairModel->insert($data)) {
            // Auto-sync kondisi aset
            if (!empty($data['kondisi_akhir'])) {
                $this->assetModel->update($data['asset_id'], [
                    'kondisi' => $data['kondisi_akhir'],
                ]);
            }

            $responseBuilder->buildResponse(201, true, 'Riwayat perbaikan berhasil ditambahkan.', [
                'id' => $this->repairModel->getInsertID(),
            ]);
        } else {
            $responseBuilder->buildResponse(400, false, 'Gagal menyimpan data.', $this->repairModel->errors());
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Update repair record
     */
    public function update($id = null)
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->can('repairs.manage')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }

        $existing = $this->repairModel->find($id);
        $data = $this->request->getJSON(true);

        // asset_id tidak boleh diubah lewat update — cegah riwayat "pindah" aset
        unset($data['asset_id']);

        $rules = [
            'tanggal'       => 'permit_empty|valid_date',
            'deskripsi'     => 'permit_empty',
            'biaya'         => 'permit_empty|decimal',
            'status_akhir'  => 'permit_empty|in_list[selesai,pending,gagal]',
            'kondisi_akhir' => 'permit_empty|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validate($rules, $data)) {
            $responseBuilder->buildResponse(422, false, 'Validation failed', $this->validator->getErrors());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        if ($this->repairModel->update($id, $data)) {
            // Auto-sync kondisi aset jika kondisi_akhir diubah
            if (!empty($data['kondisi_akhir'])) {
                $this->assetModel->update($existing['asset_id'], [
                    'kondisi' => $data['kondisi_akhir'],
                ]);
            }

            $responseBuilder->buildResponse(200, true, 'Riwayat berhasil diperbarui.');
        } else {
            $responseBuilder->buildResponse(400, false, 'Gagal memperbarui data.', $this->repairModel->errors());
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Delete repair record
     */
    public function delete($id = null)
    {
        $responseBuilder = new JSONResponseBuilder();

        if (! auth()->user()->inGroup('superadmin')) {
            $responseBuilder->buildResponse(403, false, 'Akses ditolak.');
            return $this->respond($responseBuilder, 403);
        }
        $existing = $this->repairModel->find($id);

        if (!$existing) {
            $responseBuilder->buildResponse(404, false, 'Riwayat tidak ditemukan.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        if ($this->repairModel->delete($id)) {
            $responseBuilder->buildResponse(200, true, 'Riwayat berhasil dihapus.');
        } else {
            $responseBuilder->buildResponse(400, false, 'Gagal menghapus data.');
        }

        return $this->respond($responseBuilder, $responseBuilder->code);
    }
}
