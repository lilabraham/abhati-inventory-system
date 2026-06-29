<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class FirstUserSeeder extends Seeder
{
    private const EMAIL    = 'admin@abhati.com';
    private const USERNAME = 'superadmin';
    private const GROUP    = 'superadmin';

    public function run(): void
    {
        /** @var \CodeIgniter\Shield\Models\UserModel $users */
        $users = auth()->getProvider();
        $db    = \Config\Database::connect();

        // ── Idempotent guard — cek kedua tabel sekaligus ──────────────────
        // Cek username di tabel users (root cause bug duplicate sebelumnya)
        $usernameExists = $db->table('users')
            ->where('username', self::USERNAME)
            ->countAllResults() > 0;

        // Cek email di tabel auth_identities
        $emailExists = $users->findByCredentials(['email' => self::EMAIL]) !== null;

        if ($usernameExists || $emailExists) {
            echo "  [SKIP] User '" . self::EMAIL . "' sudah ada. Seeder tidak dijalankan ulang.\n";
            return;
        }

        // ── Password dari .env, fallback ke default ────────────────────────
        // Set SEED_ADMIN_PASSWORD di .env untuk production deployment
        $password = env('SEED_ADMIN_PASSWORD', 'Admin123!');

        // ── Atomic transaction — semua step berhasil atau semua rollback ───
        $db->transStart();

        try {
            $user = new User([
                'username' => self::USERNAME,
                'email'    => self::EMAIL,
                'password' => $password,
            ]);

            if (! $users->save($user)) {
                throw new \RuntimeException(
                    'Gagal menyimpan user: ' . implode(', ', $users->errors())
                );
            }

            // ── Fetch ulang agar $user->id pasti ter-populate ───────────────
            $user = $users->findById($users->getInsertID());
            if ($user === null) {
                throw new \RuntimeException('Gagal mengambil user setelah insert.');
            }

            // ── Aktifkan user (bypass email verification) ────────────────────
            $users->activate($user);

            // ── Assign grup superadmin ───────────────────────────────────────
            $user->addGroup(self::GROUP);
        } catch (\Throwable $e) {
            $db->transRollback();
            throw new \RuntimeException('[FirstUserSeeder] ' . $e->getMessage());
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('[FirstUserSeeder] Transaksi gagal, seluruh proses dibatalkan.');
        }

        echo "  [OK] User '" . self::EMAIL . "' berhasil dibuat dan di-assign ke grup '" . self::GROUP . "'.\n";
    }
}
