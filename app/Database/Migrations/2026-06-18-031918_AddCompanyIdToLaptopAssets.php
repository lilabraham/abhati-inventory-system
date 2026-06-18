<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyIdToLaptopAssets extends Migration
{
    public function up()
    {
        $this->forge->addColumn('laptop_assets', [
            'company_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'SET NULL', 'fk_laptop_assets_company');
        $this->forge->processIndexes('laptop_assets');
    }

    public function down()
    {
        $this->forge->dropForeignKey('laptop_assets', 'fk_laptop_assets_company');
        $this->forge->dropColumn('laptop_assets', 'company_id');
    }
}
