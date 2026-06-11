<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLaptopAssets extends Migration
{ // <-- Ini kurung kurawal yang tertinggal dari Claude

    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'kode_aset'     => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'merk'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'model'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'serial_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'spesifikasi'   => ['type' => 'TEXT', 'null' => true],
            'kondisi'       => ['type' => 'ENUM', 'constraint' => ['baik','rusak','dalam_perbaikan','tidak_aktif'], 'default' => 'baik'],
            'lokasi'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'pengguna'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_beli'  => ['type' => 'DATE', 'null' => true],
            'harga_beli'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('laptop_assets');
    }

    // Fungsi down wajib ada untuk rollback/menghapus tabel jika terjadi kesalahan
    public function down(): void
    {
        $this->forge->dropTable('laptop_assets');
    }
}