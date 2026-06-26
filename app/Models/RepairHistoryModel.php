<?php

namespace App\Models;

use CodeIgniter\Model;

class RepairHistoryModel extends Model
{
    protected $table            = 'repair_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false; // Riwayat perbaikan immutable — financial & operational record

    protected $allowedFields = [
        'asset_id',
        'tanggal',
        'deskripsi',
        'teknisi',
        'biaya',
        'status_akhir',
        'kondisi_akhir',
        'created_by',
    ];

    public function getByAsset(int $assetId, int $limit = 200): array
    {
        if ($assetId <= 0) return [];

        return $this->where('asset_id', $assetId)
            ->orderBy('tanggal', 'DESC')
            ->findAll($limit);
    }
}
