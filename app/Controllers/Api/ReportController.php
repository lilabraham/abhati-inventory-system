<?php
// File: app/Controllers/Api/ReportController.php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AssetModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

// Import library PhpSpreadsheet untuk Export Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportController extends BaseController
{
    protected \CodeIgniter\Database\BaseConnection $db;
    protected AssetModel $assetModel;

    /**
     * INISIALISASI ENTERPRISE
     * Dependency Injection dilakukan satu kali di sini agar hemat memori.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        $this->db         = \Config\Database::connect();
        $this->assetModel = new AssetModel();
    }

    public function summary(): ResponseInterface
    {
        $kondisiStats = $this->db->table('laptop_assets')
            ->select('kondisi, COUNT(*) as total')
            ->where('deleted_at IS NULL') // ✅ FIXED: Bug SQL diperbaiki
            ->groupBy('kondisi')
            ->get()
            ->getResultArray();

        $totalBiaya = $this->db->table('repair_history')
            ->selectSum('biaya')
            ->get()
            ->getRow()->biaya ?? 0;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'kondisi_stats'         => $kondisiStats,
                'total_biaya_perbaikan' => (float) $totalBiaya,
                'total_aset'            => $this->assetModel->countAllResults(),
                'generated_at'          => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function assets(): ResponseInterface
    {
        // Limit maksimal 500 untuk mencegah database throttling
        $limit  = min((int) ($this->request->getGet('limit') ?? 500), 500);
        $page   = (int) ($this->request->getGet('page') ?? 1);

        $assets = $this->assetModel->withRepairCount($limit, $page);
        $pager  = $this->assetModel->pager;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $assets,
            'pager'  => [
                'current_page' => $pager->getCurrentPage(),
                'total'        => $pager->getTotal(),
                'total_pages'  => $pager->getPageCount(),
            ],
        ]);
    }

    /**
     * ENDPOINT EXPORT EXCEL
     * Diintegrasikan dari logic Claude yang sudah disempurnakan
     */
    public function exportExcel(): void
    {
        // 1. GANTI BLOK QUERY INI
        $assets = $this->db->table('laptop_assets')
            ->select('
        laptop_assets.kode_aset,
        laptop_assets.merk,
        laptop_assets.model,
        laptop_assets.pengguna,
        laptop_assets.kondisi,
        laptop_assets.lokasi,
        laptop_assets.tanggal_beli,
        laptop_assets.harga_beli,
        (SELECT COUNT(*) FROM repair_history rh WHERE rh.asset_id = laptop_assets.id) AS total_perbaikan
    ')
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
            $sheet->setCellValue("F{$r}", ($row['total_perbaikan'] ?? 0) . 'x');
            $sheet->setCellValue("G{$r}", $row['tanggal_beli']);
            $sheet->setCellValue("H{$r}", (float) ($row['harga_beli'] ?? 0));
        }

        $lastRow = count($assets) + 1;
        $sheet->getStyle("H2:H{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');

        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Aset_' . date('Ymd_His') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
