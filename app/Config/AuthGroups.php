<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    public string $defaultGroup = 'editor';

    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Akses penuh ke seluruh sistem.',
        ],
        'editor' => [
            'title'       => 'Editor',
            'description' => 'Dapat melihat aset, melihat dan menambah riwayat perbaikan.',
        ],
    ];

    public array $permissions = [
        'assets.view'    => 'Lihat data asset',
        'assets.manage'  => 'Tambah, edit, hapus asset',
        'repairs.view'   => 'Lihat riwayat perbaikan',
        'repairs.manage' => 'Tambah dan edit riwayat perbaikan',
        'reports.view'   => 'Akses dan export laporan Excel dan PDF',
        'imports.run'    => 'Import data via Excel',
        'users.view'     => 'Lihat daftar user',
        'users.manage'   => 'Buat, ban, dan hapus user',
        'audit.view'     => 'Lihat audit trail sistem',
    ];

    public array $matrix = [
        'superadmin' => ['assets.*', 'repairs.*', 'reports.*', 'imports.*', 'users.*', 'audit.view'],
        'editor'     => [
            'assets.view',
            'repairs.view',
            'repairs.manage',
        ],
    ];
}
