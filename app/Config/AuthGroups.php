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
            'description' => 'Hanya dapat menambah riwayat perbaikan.',
        ],
    ];

    public array $permissions = [
        'assets.view'    => 'Lihat data asset',
        'assets.manage'  => 'Tambah, edit, hapus asset',
        'repairs.view'   => 'Lihat riwayat perbaikan',
        'repairs.manage' => 'Tambah dan edit riwayat perbaikan',
        'reports.export' => 'Export laporan Excel dan PDF',
        'imports.run'    => 'Import data via Excel',
        'users.view'     => 'Lihat daftar user',
        'users.manage'   => 'Buat, ban, dan hapus user',
    ];

    public array $matrix = [
        'superadmin' => ['assets.*', 'repairs.*', 'reports.*', 'imports.*', 'users.*'],
        'editor'     => [
            'assets.view',
            'repairs.view',
            'repairs.manage',
        ],
    ];
}
