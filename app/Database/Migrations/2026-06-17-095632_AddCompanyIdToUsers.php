<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'company_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'SET NULL', 'fk_users_company');
        $this->forge->processIndexes('users');
    }

    public function down()
    {
        $this->forge->dropForeignKey('users', 'fk_users_company');
        $this->forge->dropColumn('users', 'company_id');
    }
}
