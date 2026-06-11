<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DummyAssetSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Dummy Laptop (Real-World Enterprise Feel)
        $laptops = [
            [
                'kode_aset'     => 'IT-LT-2024-001',
                'merk'          => 'Lenovo',
                'model'         => 'ThinkPad X1 Carbon Gen 10',
                'serial_number' => 'PF3V9KL2',
                'spesifikasi'   => 'Intel Core i7-1260P, 16GB RAM, 512GB SSD NVMe',
                'kondisi'       => 'baik',
                'lokasi'        => 'Ruang IT Lantai 2',
                'pengguna'      => 'Budi Santoso (Backend Dev)',
                'tanggal_beli'  => '2024-01-15',
                'harga_beli'    => 24500000.00,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'kode_aset'     => 'IT-LT-2024-002',
                'merk'          => 'Apple',
                'model'         => 'MacBook Pro M2 14-inch',
                'serial_number' => 'C02H1345XYZ',
                'spesifikasi'   => 'Apple M2 Pro, 16GB RAM, 512GB SSD',
                'kondisi'       => 'dalam_perbaikan',
                'lokasi'        => 'Ruang IT Lantai 2',
                'pengguna'      => 'Sarah Amalia (UI/UX Designer)',
                'tanggal_beli'  => '2024-02-10',
                'harga_beli'    => 32000000.00,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'kode_aset'     => 'IT-LT-2024-003',
                'merk'          => 'Dell',
                'model'         => 'Latitude 5430',
                'serial_number' => 'DL8900XX',
                'spesifikasi'   => 'Intel Core i5-1245U, 8GB RAM, 256GB SSD',
                'kondisi'       => 'rusak',
                'lokasi'        => 'Gudang Aset',
                'pengguna'      => 'Aset Cadangan',
                'tanggal_beli'  => '2023-11-05',
                'harga_beli'    => 15000000.00,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        // Insert ke tabel laptop_assets
        $this->db->table('laptop_assets')->insertBatch($laptops);

        // Ambil ID dari MacBook yang baru saja di-insert (untuk relasi history perbaikan)
        $macbookId = $this->db->table('laptop_assets')->where('kode_aset', 'IT-LT-2024-002')->get()->getRow()->id;

        // 2. Data Dummy History Perbaikan (Terkoneksi ke MacBook)
        $repairs = [
            [
                'asset_id'     => $macbookId,
                'tanggal'      => '2024-05-10',
                'deskripsi'    => 'Layar bergaris horizontal (LCD Artefact). Pengajuan klaim garansi iBox.',
                'teknisi'      => 'Ahmad IT Support',
                'biaya'        => 0.00, // Gratis karena garansi
                'status_akhir' => 'pending',
                'created_at'   => Time::now(),
                'updated_at'   => Time::now(),
            ]
        ];

        // Insert ke tabel repair_history
        $this->db->table('repair_history')->insertBatch($repairs);
    }
}