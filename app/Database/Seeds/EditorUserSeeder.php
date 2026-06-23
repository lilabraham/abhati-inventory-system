<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class EditorUserSeeder extends Seeder
{
    public function run(): void
    {
        /** @var \CodeIgniter\Shield\Models\UserModel $users */
        $users = auth()->getProvider();

        $email    = 'editor@abhati.com';
        $username = 'editor';

        if ($users->findByCredentials(['email' => $email]) !== null) {
            echo "  [SKIP] User '{$email}' sudah ada. Seeder tidak dijalankan ulang.\n";
            return;
        }

        $user = new User([
            'username' => $username,
            'email'    => $email,
            'password' => 'Editor123!',
        ]);

        if (! $users->save($user)) {
            throw new \RuntimeException(
                'Gagal menyimpan user: ' . implode(', ', $users->errors())
            );
        }

        $userId    = $users->getInsertID();
        $savedUser = $users->findById($userId);

        $users->update($userId, ['active' => 1]);

        $savedUser->addGroup('editor');

        echo "  [OK] User '{$email}' berhasil dibuat dan di-assign ke grup 'editor'.\n";
    }
}