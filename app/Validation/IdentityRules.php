<?php

namespace App\Validation;

use CodeIgniter\Shield\Models\UserIdentityModel;

class IdentityRules
{
    public function email_unique(string $str, ?string &$error = null): bool
    {
        $exists = (new UserIdentityModel())
            ->where('type', 'email_password')
            ->where('secret', $str)
            ->countAllResults() > 0;

        if ($exists) {
            $error = 'Email sudah digunakan.';
        }

        return ! $exists;
    }
}