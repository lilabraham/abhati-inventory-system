<?php

namespace App\Controllers;

class RepairController extends BaseController
{
    public function index()
    {
        if (! auth()->user()?->can('repairs.view')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        return view('layouts/main', [
            'title'   => 'Riwayat Perbaikan — Abhati Group',
            'content' => view('repairs/index'),
        ]);
    }
}