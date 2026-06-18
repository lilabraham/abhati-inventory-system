<?php

namespace App\Models;

use CodeIgniter\Model;

class RepairHistoryModel extends Model
{
    protected $table         = 'repair_history';
    protected $primaryKey    = 'id';
    protected $useTimestamps  = true;
    protected $allowedFields = [
        'asset_id',
        'company_id',
        'tanggal',
        'deskripsi',
        'teknisi',
        'biaya',
        'status_akhir',
    ];

    public function getByAsset(int $assetId): array
    {
        return $this->where('asset_id', $assetId)
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }
}