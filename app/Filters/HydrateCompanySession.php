<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class HydrateCompanySession implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!auth()->loggedIn()) return;
        if (!empty(session()->get('current_company'))) return;

        $companyId = db_connect()
            ->table('users')
            ->select('company_id')
            ->where('id', auth()->id())
            ->get()
            ->getRow()?->company_id;

        if ($companyId) {
            session()->set('current_company', $companyId);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}