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

    // Mapping header Excel → kolom DB (urutan wajib sesuai template)
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

    // LALU DI DALAM FUNGSI validateRow(), HAPUS VALIDASI 'nama_aset' karena di formmu tidak ada:
    // Hapus: if (empty($row['nama_aset'])) { ... }

    public function __construct(
        private AssetModel   $assetModel,
        private AuditLogModel $auditModel
    ) {}

    /**
     * Entry point utama. Return summary result.
     *
     * @return array{imported: int, failed: int, errors: array}
     */
    public function importFromFile(UploadedFile $file, int $companyId): array
    {
        $this->validateFile($file);

        $path   = $file->getTempName();
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);

        // Baca header dulu untuk validasi struktur
        $reader->setReadFilter(new ChunkReadFilter(1, 1));
        $sheet   = $reader->load($path)->getActiveSheet();
        $headers = $this->parseHeaders($sheet);

        // Hitung total baris (tanpa load semua data)
        $reader->setReadFilter(new ChunkReadFilter(1, 1));
        $fullSheet  = IOFactory::createReader('Xlsx')->load($path)->getActiveSheet();
        $totalRows  = $fullSheet->getHighestDataRow();

        if ($totalRows - 1 > self::MAX_ROWS) {
            throw new \RuntimeException('Maksimal ' . self::MAX_ROWS . ' baris per upload. File ini memiliki ' . ($totalRows - 1) . ' baris.');
        }

        // Proses per chunk
        $imported = 0;
        $failed   = 0;
        $errors   = [];

        for ($startRow = 2; $startRow <= $totalRows; $startRow += self::CHUNK_SIZE) {
            $endRow  = min($startRow + self::CHUNK_SIZE - 1, $totalRows);
            $reader->setReadFilter(new ChunkReadFilter($startRow, $endRow));
            $sheet   = $reader->load($path)->getActiveSheet();

            [$chunkImported, $chunkFailed, $chunkErrors] = $this->processChunk(
                $sheet,
                $headers,
                $startRow,
                $endRow,
                $companyId  // ← tambah ini
            );

            $imported += $chunkImported;
            $failed   += $chunkFailed;
            $errors    = array_merge($errors, $chunkErrors);
        }

        // Audit log
        $this->auditModel->insertLog([
            'user_id'     => session()->get('user_id'), // Tambahkan user yang sedang login
            'action'      => 'IMPORT',
            'module'      => 'Asset Laptop',
            'record_type' => 'assets',
            'description' => "Import Excel: {$imported} berhasil, {$failed} gagal.",
            'status'      => $failed === 0 ? 'success' : 'failed',
        ]);

        return compact('imported', 'failed', 'errors');
    }

    // ─── Private helpers ──────────────────────────────────────────

    private function validateFile(UploadedFile $file): void // <-- UBAH DI SINI
    {
        if (!$file->isValid()) {
            throw new \RuntimeException('File tidak valid atau corrupt.');
        }

        $mime = $file->getMimeType();
        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            throw new \RuntimeException('Tipe file tidak diizinkan. Hanya .xlsx yang diterima.');
        }

        // UBAH METODE INI SESUAI VERSI CI4 TERBARU
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
        int $endRow,
        int $companyId  // ← tambah ini
    ): array {
        $validRows = [];
        $errors    = [];
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

                        // [FIX] Terjemahkan angka seri Excel khusus untuk kolom tanggal_beli
                        if ($dbColumn === 'tanggal_beli' && !empty($cellValue)) {
                            // Jika Excel mengirim angka (Serial Date)
                            if (is_numeric($cellValue)) {
                                $cellValue = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue)->format('Y-m-d');
                            }
                            // Jika Excel mengirim teks dengan format lain (misal DD/MM/YYYY)
                            elseif ($parsed = strtotime((string) $cellValue)) {
                                $cellValue = date('Y-m-d', $parsed);
                            }
                        }

                        $row[$dbColumn] = trim((string) $cellValue);
                    }
                    $col++;
                }
            }

            // Skip baris kosong
            if (empty(array_filter($row))) continue;

            // Validasi per baris
            $rowErrors = $this->validateRow($row, $rowIndex);
            if (!empty($rowErrors)) {
                $errors[] = ['row' => $rowIndex, 'errors' => $rowErrors];
                continue;
            }

            // Cek duplikat kode_aset di DB
            if (
                $this->assetModel
                ->where('company_id', $companyId)   // ← tambah ini
                ->where('kode_aset', $row['kode_aset'])
                ->countAllResults() > 0
            ) {
                $errors[] = ['row' => $rowIndex, 'errors' => ["Kode aset '{$row['kode_aset']}' sudah ada di database."]];
                continue;
            }

            $validRows[] = array_merge($row, ['company_id' => $companyId]);
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
        // Ubah baris ini agar sama persis dengan opsi dropdown di UI
        $allowedKondisi = ['baik', 'rusak', 'dalam_perbaikan', 'tidak_aktif'];

        if (!empty($row['kondisi']) && !in_array(strtolower($row['kondisi']), $allowedKondisi, true)) {
            $errors[] = 'Kondisi harus salah satu dari: ' . implode(', ', $allowedKondisi) . '.';
        }

        return $errors;
    }
}
