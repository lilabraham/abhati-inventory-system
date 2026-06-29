<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuditController extends BaseController
{
    public function index()
    {
        if (! auth()->user()?->can('audit.view')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        return view('layouts/main', [
            'title'   => 'Audit Trail — Abhati Group',
            'content' => view('audit/index'),
        ]);
    }
}