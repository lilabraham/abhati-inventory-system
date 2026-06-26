<?php

namespace App\Services;

use App\Models\AuditLogModel;
use CodeIgniter\Shield\Entities\User;

class UserService
{
    private const ROLE_SUPERADMIN = 'superadmin';
    private const ROLE_EDITOR     = 'editor';

    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    private function provider()
    {
        return auth()->getProvider();
    }
    public function list(): array
    {
        $users = $this->provider()->findAll();

        return array_map(function (User $user) {
            return [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->getEmail(),
                'group'    => $user->getGroups()[0] ?? null,
                'active'   => $user->active,
                'status'   => $user->status,
                'banned'   => $user->isBanned(),
            ];
        }, $users);
    }

    public function create(array $data): User|string
    {
        $provider = $this->provider();
        $db       = db_connect();

        $db->transStart();

        $user = new User([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        if (! $provider->save($user)) {
            $db->transRollback();
            return implode(', ', $provider->errors());
        }

        $user = $provider->findById($provider->getInsertID());
        $user->addGroup(self::ROLE_EDITOR);
        $provider->activate($user);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return 'Gagal membuat user, transaksi dibatalkan.';
        }

        $this->auditLogModel->insertLog([
            'action'      => 'CREATE',
            'module'      => 'users',
            'record_type' => 'users',
            'record_id'   => $user->id,
            'description' => "User '{$user->username}' dibuat dengan role editor.",
        ]);

        return $user;
    }

    // AFTER
    public function ban(int $id, string $reason = 'Dinonaktifkan oleh admin.'): bool|string
    {
        $user = $this->provider()->findById($id);

        if (! $user) return 'User tidak ditemukan.';
        if ($user->inGroup(self::ROLE_SUPERADMIN)) return 'Superadmin tidak dapat di-ban.';
        if ($user->isBanned()) return 'User sudah dalam status banned.';

        $user->ban($reason);

        $this->auditLogModel->insertLog([
            'action'      => 'UPDATE',
            'module'      => 'users',
            'record_type' => 'users',
            'record_id'   => $id,
            'description' => "User '{$user->username}' di-ban. Alasan: {$reason}",
        ]);

        return true;
    }
    
    public function unban(int $id): bool|string
    {
        $user = $this->provider()->findById($id);

        if (! $user) return 'User tidak ditemukan.';
        if ($user->inGroup(self::ROLE_SUPERADMIN)) return 'Superadmin tidak memiliki status banned.';
        if (! $user->isBanned()) return 'User tidak dalam status banned.';

        $user->unBan();

        $this->auditLogModel->insertLog([
            'action'      => 'UPDATE',
            'module'      => 'users',
            'record_type' => 'users',
            'record_id'   => $id,
            'description' => "User '{$user->username}' di-unban.",
        ]);

        return true;
    }

    // AFTER
    public function delete(int $id): bool|string
    {
        $provider = $this->provider();
        $user     = $provider->findById($id);

        if (! $user) return 'User tidak ditemukan.';
        if ($user->inGroup(self::ROLE_SUPERADMIN)) return 'Superadmin tidak dapat dihapus.';

        $username = $user->username; // simpan sebelum soft-delete agar tetap bisa dicatat
        $result   = $provider->delete($id, false); // false = soft delete, deleted_at terisi

        if ($result) {
            $this->auditLogModel->insertLog([
                'action'      => 'DELETE',
                'module'      => 'users',
                'record_type' => 'users',
                'record_id'   => $id,
                'description' => "User '{$username}' dihapus (soft delete).",
            ]);
        }

        return $result;
    }
}
