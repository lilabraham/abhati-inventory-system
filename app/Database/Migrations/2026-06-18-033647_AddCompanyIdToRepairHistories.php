<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyIdToRepairHistories extends Migration
{
    public function up()
    {
        $this->forge->addColumn('repair_history', [  // ← fix nama tabel
            'company_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'SET NULL', 'fk_repair_history_company');
        $this->forge->processIndexes('repair_history');
    }

    public function down()
    {
        $this->forge->dropForeignKey('repair_history', 'fk_repair_history_company');
        $this->forge->dropColumn('repair_history', 'company_id');
    }
}
