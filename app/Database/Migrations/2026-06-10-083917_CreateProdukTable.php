<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdukTable extends Migration
{
    public function up()
    {
        // 1. Definisikan kolom tabel
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // 2. Set Primary Key ke kolom 'id'
        $this->forge->addKey('id', true);

        // 3. Eksekusi pembuatan tabel dengan nama 'produk'
        $this->forge->createTable('produk');
    }

    public function down()
    {
        // Jika migrasi dibatalkan (rollback), hapus tabel 'produk'
        $this->forge->dropTable('produk');
    }
}