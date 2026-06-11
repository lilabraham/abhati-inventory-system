<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DummyAssetSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan tabel lama agar tidak error "Duplicate Entry" saat dijalankan berulang
        $this->db->table('repair_history')->emptyTable();
        $this->db->table('laptop_assets')->emptyTable();
        $this->db->query('ALTER TABLE laptop_assets AUTO_INCREMENT = 1');
        $this->db->query('ALTER TABLE repair_history AUTO_INCREMENT = 1');

        // 2. Siapkan data mentah (Kamus Data) untuk diacak
        $merks = ['Lenovo', 'Apple', 'Dell', 'HP', 'Asus', 'Acer'];
        // Proporsi kondisi dibanyakin 'baik' agar serealistis kondisi kantor
        $kondisiList = ['baik', 'baik', 'baik', 'rusak', 'dalam_perbaikan', 'tidak_aktif']; 
        $lokasiList = ['Ruang IT', 'Gudang Aset', 'Lantai 1 - HRD', 'Lantai 2 - Finance', 'Ruang Manager', 'Lantai 3 - Marketing'];

        $laptops = [];
        
        // 3. Generate 100 Data Laptop
        for ($i = 1; $i <= 100; $i++) {
            $merk = $merks[array_rand($merks)];
            
            $laptops[] = [
                // Format: ABT-LT-001, ABT-LT-002, dst.
                'kode_aset'     => 'ABT-LT-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'merk'          => $merk,
                'model'         => $merk . ' ProBook ' . rand(1000, 9000) . ' Series',
                'serial_number' => strtoupper(substr(md5(rand()), 0, 10)), // Generate SN acak
                'spesifikasi'   => 'Intel Core i' . (rand(0, 1) ? '5' : '7') . ', ' . (rand(0, 1) ? '8GB' : '16GB') . ' RAM, 512GB SSD',
                'kondisi'       => $kondisiList[array_rand($kondisiList)],
                'lokasi'        => $lokasiList[array_rand($lokasiList)],
                'pengguna'      => 'Karyawan ' . $i,
                'tanggal_beli'  => date('Y-m-d', strtotime('-' . rand(10, 1000) . ' days')), // Tanggal beli acak
                'harga_beli'    => rand(10, 25) * 1000000, // Harga antara 10 - 25 Juta
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ];
        }

        // Insert sekaligus 100 data (InsertBatch lebih cepat dari Insert biasa)
        $this->db->table('laptop_assets')->insertBatch($laptops);

        // 4. Generate Data History Perbaikan secara acak untuk 20 laptop pertama
        $repairs = [];
        for ($j = 1; $j <= 20; $j++) {
            $repairs[] = [
                'asset_id'     => rand(1, 100), // Acak nempel ke laptop ID mana
                'tanggal'      => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
                'deskripsi'    => 'Pengecekan rutin dan pembersihan hardware / ganti pasta.',
                'teknisi'      => 'Ahmad IT Support',
                'biaya'        => rand(2, 8) * 50000,
                'status_akhir' => 'selesai',
                'created_at'   => Time::now(),
                'updated_at'   => Time::now(),
            ];
        }

        $this->db->table('repair_history')->insertBatch($repairs);
    }
}