<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;

class AuditAPI extends BaseController
{
    use ResponseTrait;

    protected $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Insert audit log entry from frontend (AJAX)
     */
    public function log()
    {
        $responseBuilder = new JSONResponseBuilder();

        $contentType = $this->request->getHeaderLine('Content-Type');
        $data = str_contains($contentType, 'application/json')
            ? ($this->request->getJSON(true) ?? [])
            : $this->request->getPost();

        $rules = [
            'action' => 'required|max_length[50]',
            'module' => 'required|max_length[100]',
        ];

        if (!$this->validateData($data, $rules)) {
            $responseBuilder->buildResponse(422, false, 'Validation failed', $this->validator->getErrors());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $this->auditLogModel->insertLog([
            'action'      => strtoupper($data['action']),
            'module'      => $data['module'],
            'record_type' => $data['record_type'] ?? 'assets',
            'description' => $data['description'] ?? '',
            'status'      => 'success',
        ]);

        $responseBuilder->buildResponse(201, true, 'Log recorded successfully');
        return $this->respond($responseBuilder, $responseBuilder->code);
    }
}
