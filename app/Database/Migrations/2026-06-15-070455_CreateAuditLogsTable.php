<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',   // INT cukup ~2 miliar, BIGINT untuk truly enterprise scale
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['CREATE', 'READ', 'UPDATE', 'DELETE', 'EXPORT', 'IMPORT', 'LOGIN', 'LOGOUT', 'FAILED_LOGIN'],
                // ENUM lebih ketat dari VARCHAR — mencegah typo, lebih efisien storage
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            // TAMBAHAN KRITIS: Referensi ke data yang dimodifikasi
            'record_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,   // e.g. 'assets', 'users', 'reports'
            ],
            'record_id' => [
                'type'    => 'BIGINT',
                'null'    => true,      // ID dari row yang dimodifikasi
            ],
            // TAMBAHAN KRITIS: Snapshot before/after
            'old_value' => [
                'type' => 'JSON',
                'null' => true,         // State data SEBELUM perubahan
            ],
            'new_value' => [
                'type' => 'JSON',
                'null' => true,         // State data SESUDAH perubahan
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // TAMBAHAN PENTING: Forensik & tracking
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
            ],
            'request_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,   // UUID per HTTP request, untuk group batch ops
            ],
            // Status untuk async logging (jika pakai queue)
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['success', 'failed'],
                'default'    => 'success',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('action');
        $this->forge->addKey('created_at');              // ✅ Untuk range query by date
        $this->forge->addKey(['record_type', 'record_id']); // ✅ "Siapa yg ubah asset #457?"
        $this->forge->addKey(['user_id', 'created_at']); // ✅ "Semua aksi user X bulan ini"

        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}