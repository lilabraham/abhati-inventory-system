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

    public function withRepairCount(): array
    {
        return $this->db->table('laptop_assets la')
            ->select('la.*, COUNT(rh.id) as total_perbaikan')
            ->join('repair_history rh', 'rh.asset_id = la.id', 'left')
            ->where('la.deleted_at', null)
            ->groupBy('la.id')
            ->orderBy('la.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}