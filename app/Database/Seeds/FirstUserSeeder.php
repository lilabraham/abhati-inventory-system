<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class FirstUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ambil provider user bawaan CI4 Shield ──────────────────────────
        /** @var \CodeIgniter\Shield\Models\UserModel $users */
        $users = auth()->getProvider();

        $email    = 'admin@abhati.com';
        $username = 'superadmin';

        // ── Cek eksistensi: idempotent guard ───────────────────────────────
        // Jika user dengan email ini sudah ada, skip seluruh proses.
        // Aman dijalankan berulang kali (deployment pipeline, CI/CD).
        if ($users->findByCredentials(['email' => $email]) !== null) {
            echo "  [SKIP] User '{$email}' sudah ada. Seeder tidak dijalankan ulang.\n";
            return;
        }

        // ── Buat entitas User baru via Shield Entity ───────────────────────
        // Menggunakan Entity resmi Shield agar lifecycle hooks & casting
        // (password hashing, dll.) berjalan secara otomatis.
        $user = new User([
            'username' => $username,
            'email'    => $email,
            'password' => 'Admin123!',
        ]);

        // ── Simpan user ke database ────────────────────────────────────────
        // Shield akan otomatis hash password via `casts` di Entity-nya.
        if (! $users->save($user)) {
            // Lempar exception agar proses seeder gagal secara eksplisit
            // dan tidak diam-diam melanjutkan ke step berikutnya.
            throw new \RuntimeException(
                'Gagal menyimpan user: ' . implode(', ', $users->errors())
            );
        }

        // ── Ambil ID user yang baru saja dibuat ───────────────────────────
        $userId      = $users->getInsertID();
        $savedUser   = $users->findById($userId);

        // ── Aktifkan email user (bypass verifikasi untuk initial seed) ─────
        // Tanpa ini, user tidak bisa login karena status belum "active".
        $users->update($userId, ['active' => 1]);

        // ── Assign ke grup superadmin ──────────────────────────────────────
        // Grup 'superadmin' harus sudah terdefinisi di AuthGroups config.
        // Fallback ke 'admin' jika nama grup berbeda di environment Anda.
        $savedUser->addGroup('superadmin');

        echo "  [OK] User '{$email}' berhasil dibuat dan di-assign ke grup 'superadmin'.\n";
    }
}
