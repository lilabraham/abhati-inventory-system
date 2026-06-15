<?php
// File: app/Controllers/Api/AuditController.php — PATCHED
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuditController extends BaseController
{
    public function log(): ResponseInterface
    {
        $rules = [
            'action' => 'required|max_length[50]',
            'module' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // ✅ FIX: Delegate ke insertLog() bukan insert() langsung
        (new AuditLogModel())->insertLog([
            'action'      => strtoupper($this->request->getPost('action')),
            'module'      => $this->request->getPost('module'),
            'record_type' => $this->request->getPost('record_type') ?? 'assets',
            'description' => $this->request->getPost('description') ?? '',
            'status'      => 'success',
        ]);

        return $this->response->setStatusCode(204)->setBody('');
    }
}