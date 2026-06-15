<?php
// File: app/Services/ReportService.php
namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportService
{
    protected BaseConnection $db;

    public function __construct(BaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Mengambil ringkasan statistik untuk dashboard pelaporan.
     */
    public function getSummary(int $totalAset): array
    {
        $kondisiStats = $this->db->table('laptop_assets')
            ->select('kondisi, COUNT(*) as total')
            ->where('deleted_at IS NULL')
            ->groupBy('kondisi')
            ->get()
            ->getResultArray();

        $totalBiaya = $this->db->table('repair_history')
            ->selectSum('biaya')
            ->get()
            ->getRow()->biaya ?? 0;

        return [
            'kondisi_stats'         => $kondisiStats,
            'total_biaya_perbaikan' => (float) $totalBiaya,
            'total_aset'            => $totalAset,
            'generated_at'          => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Mengambil seluruh data aset untuk diekspor ke Excel.
     */
    public function getAllAssets(): array
    {
        return $this->db->table('laptop_assets')
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
    }

    /**
     * Membangun file Spreadsheet menggunakan PhpSpreadsheet.
     */
    public function buildAssetSpreadsheet(array $assets): Spreadsheet
    {
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

        return $spreadsheet;
    }

    /**
     * Mendorong file Excel langsung ke browser pengguna (Download).
     */
    public function streamExcel(Spreadsheet $spreadsheet, string $filename): void
    {
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