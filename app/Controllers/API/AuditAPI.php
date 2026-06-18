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
        parent::__construct();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Get audit logs for current company (Tabulator-ready)
     */
    public function index()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $page    = (int) ($this->request->getVar('page') ?? 1);
        $size    = (int) ($this->request->getVar('size') ?? 15);
        $search  = $this->request->getVar('search');

        $sorters = $this->request->getVar('sort') ?? $this->request->getVar('sorters');
        $filters = $this->request->getVar('filter') ?? $this->request->getVar('filters');

        if (is_string($sorters)) $sorters = json_decode($sorters, true);
        if (is_string($filters)) $filters = json_decode($filters, true);

        $query = $this->auditLogModel->where('company_id', $this->userCompanyId);

        // Tabulator filters
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $filter) {
                $field = $filter['field'] ?? '';
                $value = $filter['value'] ?? '';
                $type  = $filter['type'] ?? 'like';

                if ($value === '' || $value === null) continue;

                if ($type === 'like') {
                    $query->like($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        // Global search
        if ($search) {
            $query->groupStart()
                ->like('action', $search)
                ->orLike('module', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }

        // Tabulator sorters
        if (!empty($sorters) && is_array($sorters)) {
            foreach ($sorters as $sorter) {
                $query->orderBy($sorter['field'], strtoupper($sorter['dir']));
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $logs  = $query->paginate($size, 'default', $page);
        $total = $this->auditLogModel->pager->getTotal();

        $responseBuilder->buildResponse(200, true, 'Audit logs retrieved successfully', [
            'data'      => $logs,
            'last_page' => ceil($total / $size),
            'total'     => $total,
        ]);

        return $this->respond($responseBuilder, $responseBuilder->code);
    }

    /**
     * Insert audit log entry from frontend (AJAX)
     */
    public function log()
    {
        $responseBuilder = new JSONResponseBuilder();

        if (empty($this->userCompanyId)) {
            $responseBuilder->buildResponse(401, false, 'Company ID not found. Please login.');
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $contentType = $this->request->getHeaderLine('Content-Type');
        $data = str_contains($contentType, 'application/json')
            ? ($this->request->getJSON(true) ?? [])
            : $this->request->getPost();

        $rules = [
            'action' => 'required|max_length[50]',
            'module' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules, [], $data)) {
            $responseBuilder->buildResponse(422, false, 'Validation failed', $this->validator->getErrors());
            return $this->respond($responseBuilder, $responseBuilder->code);
        }

        $this->auditLogModel->insertLog([
            'company_id'  => $this->userCompanyId,
            'user_id'     => auth()->id(),
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
