<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKondisiAkhirToRepairHistory extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('repair_history', [
            'kondisi_akhir' => [
                'type'       => 'ENUM',
                'constraint' => ['baik', 'rusak', 'dalam_perbaikan', 'tidak_aktif'],
                'null'       => true,
                'default'    => null,
                'after'      => 'status_akhir',
            ],
            'created_by' => [
                'type'       => 'INT',
                'null'       => true,
                'default'    => null,
                'after'      => 'kondisi_akhir',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('repair_history', ['kondisi_akhir', 'created_by']);
    }
}