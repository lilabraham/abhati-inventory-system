<?php

namespace App\Controllers;

class AssetController extends BaseController
{
    public function index(): string
    {
        $user = auth()->user();

        return $this->view('assets/index', [
            'title' => 'Aset Laptop',
            'can'   => [
                'manage' => $user?->can('assets.manage') ?? false,
                'import' => $user?->can('imports.run')   ?? false,
            ],
        ]);
    }

    public function show(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (! (new \App\Models\AssetModel())->find($id)) {
            return redirect()->to(base_url('data-aset'))
                ->with('error', 'Aset tidak ditemukan.');
        }

        $user = auth()->user();

        return $this->view('assets/show', [
            'title'    => 'Detail Aset',
            'asset_id' => $id,
            'can'      => [
                'repair' => $user?->can('repairs.manage') ?? false,
            ],
        ]);
    }
}
