<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Libraries\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;

class AuditAPI extends BaseController
{
    use ResponseTrait;

    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    public function log()
    {
        if (! auth()->user()->can('assets.view')) {
            return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
        }

        $data = $this->request->getJSON(true) ?? [];
        $rules = [
            'action'      => 'required|in_list[CREATE,UPDATE,DELETE,IMPORT,EXPORT,LOGIN,LOGOUT]|max_length[50]',
            'module'      => 'required|max_length[100]',
            'record_type' => 'permit_empty|in_list[assets,repairs,users]',
            'record_id'   => 'permit_empty|integer',
            'description' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->respond(JSONResponseBuilder::make(422, false, 'Validation failed', $this->validator->getErrors()), 422);
        }

        $inserted = $this->auditLogModel->insertLog([
            'action'      => $data['action'],
            'module'      => $data['module'],
            'record_type' => $data['record_type'] ?? 'assets',
            'record_id'   => $data['record_id'] ?? null,
            'description' => $data['description'] ?? '',
        ]);

        if (! $inserted) {
            return $this->respond(JSONResponseBuilder::make(500, false, 'Gagal menyimpan log.'), 500);
        }

        return $this->respond(JSONResponseBuilder::make(201, true, 'Log recorded successfully'), 201);
    }
}
