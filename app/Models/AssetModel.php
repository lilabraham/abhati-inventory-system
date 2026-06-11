<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table         = 'laptop_assets';
    protected $primaryKey    = 'id';
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'kode_aset',
        'merk',
        'model',
        'serial_number',
        'spesifikasi',
        'kondisi',
        'lokasi',
        'pengguna',
        'tanggal_beli',
        'harga_beli',
    ];


public function withRepairCount(int $perPage = 15, int $page = 1): array
{
    return $this
        ->select('laptop_assets.*, COUNT(repair_history.id) as total_perbaikan')
        ->join('repair_history', 'repair_history.asset_id = laptop_assets.id', 'left')
        ->groupBy('laptop_assets.id')
        ->orderBy('laptop_assets.created_at', 'DESC')
        ->paginate($perPage, 'default', $page);
}
}