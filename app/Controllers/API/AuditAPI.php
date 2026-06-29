<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Libraries\JSONResponseBuilder;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class AuditAPI extends BaseController
{
    use ResponseTrait;

    private const VALID_ACTIONS = ['CREATE', 'UPDATE', 'DELETE', 'IMPORT', 'EXPORT', 'LOGIN', 'LOGOUT'];
    private const VALID_MODULES = ['assets', 'repairs', 'users'];

    protected AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
    }

    public function index(): ResponseInterface
    {
        try {
            if (! auth()->user()?->can('audit.view')) {
                return $this->respond(JSONResponseBuilder::make(403, false, 'Akses ditolak.'), 403);
            }

            $page     = max(1, min(500, (int) ($this->request->getGet('page') ?? 1)));
            $size     = max(1, min(100, (int) ($this->request->getGet('size') ?? 20)));
            $action   = trim($this->request->getGet('action') ?? '');
            $module   = trim($this->request->getGet('module') ?? '');
            $dateFrom = trim($this->request->getGet('date_from') ?? '');
            $dateTo   = trim($this->request->getGet('date_to') ?? '');

            $validDate = static fn(string $d): bool =>
            $d !== '' && \DateTime::createFromFormat('Y-m-d', $d) !== false;

            $builder = $this->auditLogModel->builder();

            if ($action !== '' && in_array($action, self::VALID_ACTIONS, true)) {
                $builder->where('action', $action);
            }

            if ($module !== '' && in_array($module, self::VALID_MODULES, true)) {
                $builder->where('record_type', $module);
            }

            if ($validDate($dateFrom)) {
                $builder->where('created_at >=', $dateFrom . ' 00:00:00');
            }

            if ($validDate($dateTo)) {
                $builder->where('created_at <=', $dateTo . ' 23:59:59');
            }

            $builder->orderBy('created_at', 'DESC');

            $total  = $builder->countAllResults(false);
            $offset = ($page - 1) * $size;
            $logs   = $builder->limit($size, $offset)->get()->getResultArray();

            return $this->respond(JSONResponseBuilder::make(200, true, 'OK', [
                'logs'        => $logs,
                'total'       => $total,
                'page'        => $page,
                'size'        => $size,
                'total_pages' => (int) ceil($total / $size),
            ]));
        } catch (\Throwable $e) {
            log_message('error', '[AuditAPI::index] ' . $e->getMessage());
            return $this->respond(JSONResponseBuilder::make(500, false, 'Terjadi kesalahan internal.'), 500);
        }
    }
}
