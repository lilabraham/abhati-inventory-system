<?php

namespace App\Services;

use App\Models\AssetModel;
use App\Models\AuditLogModel;
use CodeIgniter\Files\FileSizeUnit;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Baca hanya baris yang dibutuhkan — cegah OOM untuk file besar.
 */
class ChunkReadFilter implements IReadFilter
{
    private int $startRow;
    private int $endRow;

    public function __construct(int $startRow, int $endRow)
    {
        $this->startRow = $startRow;
        $this->endRow   = $endRow;
    }

    public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
    {
        return $row >= $this->startRow && $row <= $this->endRow;
    }
}

class ImportService
{
    private const MAX_ROWS      = 1000;
    private const CHUNK_SIZE    = 100;
    private const ALLOWED_MIME  = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    private const HEADER_MAP = [
        'kode_aset'     => 'kode_aset',
        'merk'          => 'merk',
        'model'         => 'model',
        'serial_number' => 'serial_number',
        'pengguna'      => 'pengguna',
        'kondisi'       => 'kondisi',
        'lokasi'        => 'lokasi',
        'tanggal_beli'  => 'tanggal_beli',
        'harga_beli'    => 'harga_beli',
        'spesifikasi'   => 'spesifikasi',
    ];

    public function __construct(
        private AssetModel   $assetModel,
        private AuditLogModel $auditModel
    ) {}

    /**
     * Entry point utama. Return summary result.
     *
     * @return array{imported: int, failed: int, errors: array}
     */
    public function importFromFile(UploadedFile $file): array
    {
        $this->validateFile($file);

        $path = $file->getTempName();

        $headerReader = IOFactory::createReader('Xlsx');
        $headerReader->setReadDataOnly(true);
        $headerReader->setReadFilter(new ChunkReadFilter(1, 1));
        $headerSheet = $headerReader->load($path)->getActiveSheet();
        $headers     = $this->parseHeaders($headerSheet);

        $dimensionReader = IOFactory::createReader('Xlsx');
        $dimensionReader->setReadDataOnly(true);
        $dimensionReader->setLoadSheetsOnly([$headerSheet->getTitle()]);
        $totalRows = $dimensionReader->load($path)->getActiveSheet()->getHighestDataRow();
        if ($totalRows - 1 > self::MAX_ROWS) {
            throw new \RuntimeException('Maksimal ' . self::MAX_ROWS . ' baris per upload. File ini memiliki ' . ($totalRows - 1) . ' baris.');
        }

        $imported = 0;
        $failed   = 0;
        $errors   = [];

        for ($startRow = 2; $startRow <= $totalRows; $startRow += self::CHUNK_SIZE) {
            $endRow = min($startRow + self::CHUNK_SIZE - 1, $totalRows);

            $chunkReader = IOFactory::createReader('Xlsx');
            $chunkReader->setReadDataOnly(true);
            $chunkReader->setReadFilter(new ChunkReadFilter($startRow, $endRow));
            $sheet = $chunkReader->load($path)->getActiveSheet();

            [$chunkImported, $chunkFailed, $chunkErrors] = $this->processChunk(
                $sheet,
                $headers,
                $startRow,
                $endRow
            );

            $imported += $chunkImported;
            $failed   += $chunkFailed;
            $errors    = array_merge($errors, $chunkErrors);
        }

        $this->auditModel->insertLog([
            'action'      => 'IMPORT',
            'module'      => 'Asset Laptop',
            'record_type' => 'assets',
            'description' => "Import Excel: {$imported} berhasil, {$failed} gagal.",
            'status'      => $failed === 0 ? 'success' : 'failed',
        ]);

        return compact('imported', 'failed', 'errors');
    }

    // ─── Private helpers ──────────────────────────────────────────

    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \RuntimeException('File tidak valid atau corrupt.');
        }

        $mime = $file->getMimeType();
        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            throw new \RuntimeException('Tipe file tidak diizinkan. Hanya .xlsx yang diterima.');
        }

        if ($file->getSizeByBinaryUnit(FileSizeUnit::MB) > 5) {
            throw new \RuntimeException('Ukuran file maksimal 5MB.');
        }
    }

    private function parseHeaders(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        $headers = [];
        foreach ($sheet->getRowIterator(1, 1) as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $headers[] = strtolower(trim((string) $cell->getValue()));
            }
        }

        $required = array_keys(self::HEADER_MAP);
        $missing  = array_diff($required, $headers);
        if (!empty($missing)) {
            throw new \RuntimeException('Header Excel tidak sesuai. Kolom tidak ditemukan: ' . implode(', ', $missing));
        }

        return $headers;
    }

    private function processChunk(
        \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,
        array $headers,
        int $startRow,
        int $endRow
    ): array {
        $validRows = [];
        $errors    = [];
        $seenInBatch = [];
        $db        = \Config\Database::connect();

        for ($rowIndex = $startRow; $rowIndex <= $endRow; $rowIndex++) {
            $row = [];
            $col = 0;
            foreach ($sheet->getRowIterator($rowIndex, $rowIndex) as $sheetRow) {
                foreach ($sheetRow->getCellIterator() as $cell) {
                    $key = $headers[$col] ?? null;
                    if ($key && isset(self::HEADER_MAP[$key])) {
                        $dbColumn  = self::HEADER_MAP[$key];
                        $cellValue = $cell->getValue();

                        if ($dbColumn === 'tanggal_beli' && !empty($cellValue)) {
                            if (is_numeric($cellValue)) {
                                $cellValue = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue)->format('Y-m-d');
                            } elseif ($parsed = strtotime((string) $cellValue)) {
                                $cellValue = date('Y-m-d', $parsed);
                            }
                        }

                        $row[$dbColumn] = trim((string) $cellValue);
                    }
                    $col++;
                }
            }

            if (empty(array_filter($row))) continue;

            $rowErrors = $this->validateRow($row, $rowIndex);
            if (!empty($rowErrors)) {
                $errors[] = ['row' => $rowIndex, 'errors' => $rowErrors];
                continue;
            }

            if (isset($seenInBatch[$row['kode_aset']])) {
                $errors[] = ['row' => $rowIndex, 'errors' => ["Kode aset '{$row['kode_aset']}' duplikat dengan baris {$seenInBatch[$row['kode_aset']]} di file ini."]];
                continue;
            }

            if (
                $this->assetModel
                ->where('kode_aset', $row['kode_aset'])
                ->countAllResults() > 0
            ) {
                $errors[] = ['row' => $rowIndex, 'errors' => ["Kode aset '{$row['kode_aset']}' sudah ada di database."]];
                continue;
            }

            $seenInBatch[$row['kode_aset']] = $rowIndex;
            $validRows[] = $row;
        }

        $imported = 0;
        if (!empty($validRows)) {
            $db->transStart();
            try {
                $this->assetModel->insertBatch($validRows);
                $db->transComplete();
                $imported = count($validRows);
            } catch (\Throwable $e) {
                $db->transRollback();
                throw $e;
            }
        }

        return [$imported, count($errors), $errors];
    }

    private function validateRow(array $row, int $rowIndex): array
    {
        $errors = [];

        if (empty($row['kode_aset'])) {
            $errors[] = 'Kode aset wajib diisi.';
        }
        if (!empty($row['tanggal_beli']) && !strtotime($row['tanggal_beli'])) {
            $errors[] = 'Format tanggal tidak valid (gunakan YYYY-MM-DD).';
        }
        if (!empty($row['harga_beli']) && !is_numeric($row['harga_beli'])) {
            $errors[] = 'Harga beli harus berupa angka.';
        }
        $allowedKondisi = ['baik', 'rusak', 'dalam_perbaikan', 'tidak_aktif'];

        if (!empty($row['kondisi']) && !in_array(strtolower($row['kondisi']), $allowedKondisi, true)) {
            $errors[] = 'Kondisi harus salah satu dari: ' . implode(', ', $allowedKondisi) . '.';
        }

        return $errors;
    }
}
