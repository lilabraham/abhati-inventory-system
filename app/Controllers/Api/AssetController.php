<?php
// File: app/Controllers/api/AssetController.php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;


class AssetController extends BaseController
{
    private AssetModel $model;
    private \CodeIgniter\Database\BaseConnection $db;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger); // ← wajib dipanggil pertama
        $this->model = new AssetModel();
        $this->db    = \Config\Database::connect();
    }

    public function index(): ResponseInterface
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 15);

        $assets = $this->model->withRepairCount($perPage, $page);
        $pager  = $this->model->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $assets,
            'pager'  => [
                'current_page' => $pager->getCurrentPage(),
                'per_page'     => $perPage,
                'total'        => $pager->getTotal(),
                'total_pages'  => $pager->getPageCount(),
                'has_previous' => $page > 1,
                'has_next'     => $page < $pager->getPageCount(),
            ],
        ]);
    }

    public function show(int $id): ResponseInterface
    {
        $asset = $this->model->find($id);

        if (!$asset) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $asset,
        ]);
    }

    public function store(): ResponseInterface
    {
        $data  = $this->request->getJSON(true);
        $rules = [
            'kode_aset' => 'required|is_unique[laptop_assets.kode_aset]',
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $id = $this->model->insert($data);

        return $this->response
            ->setStatusCode(201)
            ->setJSON(['status' => 'success', 'id' => $id, 'message' => 'Asset berhasil ditambahkan.']);
    }

    public function update(int $id): ResponseInterface
    {
        if (!$this->model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        $data  = $this->request->getJSON(true);
        $rules = [
            'kode_aset' => "required|max_length[20]|is_unique[laptop_assets.kode_aset,id,{$id}]",
            'merk'      => 'required|max_length[100]',
            'model'     => 'required|max_length[100]',
            'kondisi'   => 'required|in_list[baik,rusak,dalam_perbaikan,tidak_aktif]',
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }

        $this->model->update($id, $data);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Asset berhasil diperbarui.',
        ]);
    }

    public function destroy(int $id): ResponseInterface
    {
        if (!$this->model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Asset tidak ditemukan.']);
        }

        $this->model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Asset berhasil dihapus.',
        ]);
    }
    public function exportExcel(): void
    {
        $assets = $this->db->table('laptop_assets')
            ->select('laptop_assets.kode_aset, laptop_assets.merk, laptop_assets.model, users.nama AS pengguna, laptop_assets.kondisi, laptop_assets.catatan_perbaikan, laptop_assets.tanggal_beli, laptop_assets.harga_beli')
            ->join('users', 'users.id = laptop_assets.user_id', 'left')
            ->where('laptop_assets.deleted_at IS NULL')
            ->orderBy('laptop_assets.id', 'ASC')
            ->get()
            ->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Aset Laptop');

        $headers = ['No', 'Kode Aset', 'Merk/Model', 'Pengguna', 'Kondisi', 'Perbaikan', 'Tanggal Beli', 'Harga Beli'];
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($columns[$i] . '1', $header);
        }

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FF333333']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(20);

        foreach ($assets as $i => $row) {
            $r = $i + 2;
            $sheet->setCellValue("A{$r}", $i + 1);
            $sheet->setCellValue("B{$r}", $row['kode_aset']);
            $sheet->setCellValue("C{$r}", trim($row['merk'] . ' ' . $row['model']));
            $sheet->setCellValue("D{$r}", $row['pengguna'] ?? '-');
            $sheet->setCellValue("E{$r}", $row['kondisi']);
            $sheet->setCellValue("F{$r}", $row['catatan_perbaikan'] ?? '-');
            $sheet->setCellValue("G{$r}", $row['tanggal_beli']);
            $sheet->setCellValue("H{$r}", (float) ($row['harga_beli'] ?? 0)); // cast ke float
        }

        // Format currency kolom H          fix #5
        $lastRow = count($assets) + 1;
        $sheet->getStyle("H2:H{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');

        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Aset_Laptop_' . date('Ymd_His') . '.xlsx';

        if (ob_get_length()) ob_end_clean(); // fix #6

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
