<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRepairHistory extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'asset_id'    => ['type' => 'INT'],
            'tanggal'     => ['type' => 'DATE'],
            'deskripsi'   => ['type' => 'TEXT'],
            'teknisi'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'biaya'       => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'status_akhir' => ['type' => 'ENUM', 'constraint' => ['selesai', 'pending', 'gagal'], 'default' => 'selesai'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('asset_id', 'laptop_assets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('repair_history');
    }

    public function down(): void
    {
        $this->forge->dropTable('repair_history');
    }
}
