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
}