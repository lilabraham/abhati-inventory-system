<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class UserManagement extends BaseController
{
    public function index(): string|RedirectResponse
    {
        if (! auth()->user()->can('users.manage')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        return $this->view('users/index');
    }
}