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
    protected $useTimestamps    = false;  // JANGAN pakai useTimestamps — isi manual
    protected $useSoftDeletes   = false;  // Audit log tidak boleh dihapus — override delete() diblock

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
        'request_id', // opsional — caller pass manual jika ada X-Request-ID header
        'status',
    ];

    // Block operasi berbahaya di level Model
    public function update($id = null, $data = null): bool
    {
        return false;
    }

    public function delete($id = null, bool $purge = false): bool
    {
        return false;
    }

    /**
     * Satu-satunya cara menulis log. Auto-inject context dari request & session.
     */
    public function insertLog(array $data): bool
    {
        $request      = service('request');
        $loggedIn     = auth()->loggedIn();
        $userId       = $loggedIn ? auth()->id() : null;
        $username     = $loggedIn ? (auth()->user()->username ?? 'system') : 'system';
        $rawSessionId = session_id();

        $allowedCallerFields = [
            'action',
            'module',
            'record_type',
            'record_id',
            'old_value',
            'new_value',
            'description',
            'request_id',
            'status',
        ];

        $filteredData = array_intersect_key($data, array_flip($allowedCallerFields));

        return (bool) $this->db->table($this->table)->insert(array_merge($filteredData, [
            'user_id'    => $userId,
            'username'   => $username,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => substr((string) $request->getUserAgent(), 0, 500),
            'session_id' => $rawSessionId ? hash('sha256', $rawSessionId) : null,
            'status' => $filteredData['status'] ?? 'success',
            'created_at' => date('Y-m-d H:i:s'),
        ]));
    }
}
