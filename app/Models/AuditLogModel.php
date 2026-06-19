<?php

namespace App\Models;
// File: app/Models/AuditLogModel.php
use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false; // JANGAN pakai useTimestamps — isi manual

    protected $allowedFields = [
        'user_id',
        'username',
        'action',
        'module',
        'record_type',
        'record_id',
        'old_value',
        'new_value',
        'description',
        'ip_address',
        'user_agent',
        'session_id',
        'request_id',
        'status',
        'created_at',
    ];

    // Block operasi berbahaya di level Model
    public function update($id = null, $data = null): bool
    {
        return false;
    }
    public function delete($id = null, bool $purge = false)
    {
        return false;
    }

    /**
     * Satu-satunya cara menulis log. Auto-inject context dari request & session.
     */
    public function insertLog(array $data): bool
    {
        $request = service('request');

        $userId   = auth()->loggedIn() ? auth()->id() : null;
        $username = auth()->loggedIn() ? (auth()->user()->username ?? 'system') : 'system';

        return (bool) $this->insert(array_merge([
            'user_id'    => $userId,
            'username'   => $username,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => substr((string) $request->getUserAgent(), 0, 500),
            'session_id' => session_id() ?: null,
            'status'     => 'success',
            'created_at' => date('Y-m-d H:i:s'),
        ], $data));
    }
}
