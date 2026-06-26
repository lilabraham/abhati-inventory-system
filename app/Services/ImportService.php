<?php

namespace App\Services;

use App\Models\AssetModel;

class ImportService
{
    private const MAX_ROWS_PER_BATCH = 100;

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
        private AssetModel $assetModel
    ) {}

    /**
     * Process rows dari frontend (SheetJS JSON).
     * Duplikat kode_aset → skipped, bukan failed.
     *
     * @param  array<int, array<string, mixed>> $rows
     * @return array{imported: int, skipped: int, failed: int, errors: array}
     */
    public function processRows(array $rows): array
    {
        if (count($rows) > self::MAX_ROWS_PER_BATCH) {
            throw new \InvalidArgumentException(
                'Batch melebihi limit ' . self::MAX_ROWS_PER_BATCH . ' rows per request.'
            );
        }

        $imported    = 0;
        $skipped     = 0;
        $failed      = 0;
        $errors      = [];
        $validRows   = [];
        $seenInBatch = [];

        foreach ($rows as $index => $raw) {
            $rowNum = $index + 1;
            $row    = $this->normalizeRow($raw);

            if (empty(array_filter($row))) {
                continue;
            }

            // Duplikat dalam batch yang sama → skip
            if (isset($seenInBatch[$row['kode_aset']])) {
                $skipped++;
                continue;
            }

            // Duplikat di DB → skip (idempotent re-upload).
            // withDeleted() wajib: kode_aset harus unik permanen, termasuk row yang sudah di-soft-delete.
            if ($this->assetModel->withDeleted()->where('kode_aset', $row['kode_aset'])->countAllResults() > 0) {
                $skipped++;
                $seenInBatch[$row['kode_aset']] = true;
                continue;
            }

            $rowErrors = $this->validateRow($row);
            if (!empty($rowErrors)) {
                $errors[] = ['row' => $rowNum, 'errors' => $rowErrors];
                $failed++;
                continue;
            }

            $seenInBatch[$row['kode_aset']] = true;
            $validRows[] = $row;
        }

        if (!empty($validRows)) {
            $db = \Config\Database::connect();
            $db->transStart();
            $this->assetModel->insertBatch($validRows);
            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \RuntimeException('Batch insert gagal. Tidak ada data yang tersimpan.');
            }

            $imported = count($validRows);
        }

        return compact('imported', 'skipped', 'failed', 'errors');
    }

    // ─── Private helpers ───────────────────────────────────────────

    /**
     * Normalize & sanitize satu row dari SheetJS.
     * SheetJS sudah handle Excel serial date → string, tapi format bisa beragam.
     */
    private function normalizeRow(array $raw): array
    {
        $row = [];

        foreach (self::HEADER_MAP as $key => $dbCol) {
            $value = isset($raw[$key]) ? trim((string) $raw[$key]) : '';

            if ($dbCol === 'kondisi') {
                $value = strtolower($value);
            }

            if ($dbCol === 'tanggal_beli' && !empty($value)) {
                $parsed = strtotime($value);
                $value  = $parsed ? date('Y-m-d', $parsed) : '';
            }

            if ($dbCol === 'harga_beli' && !empty($value)) {
                $value = $this->parseHargaBeli($value);
            }

            $row[$dbCol] = $value;
        }

        return $row;
    }

    /**
     * Parse harga dari format umum spreadsheet ID:
     * "1.000.000" (titik=ribuan), "1.000.000,50" (koma=desimal),
     * atau plain numeric "1000000.50" / "1000000".
     */
    private function parseHargaBeli(string $value): string
    {
        $value = trim(str_replace(['Rp', 'rp', ' '], '', $value));

        $hasComma = str_contains($value, ',');
        $hasDot   = str_contains($value, '.');

        if ($hasComma && $hasDot) {
            // Format ID: titik=ribuan, koma=desimal → "1.000.000,50"
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif ($hasComma && !$hasDot) {
            // "1000000,50" → koma sebagai desimal
            $value = str_replace(',', '.', $value);
        } elseif ($hasDot && !$hasComma) {
            // Ambigu: "1.000" bisa ribuan ATAU desimal.
            // Heuristik: >2 digit setelah titik terakhir, atau >1 titik = ribuan.
            $parts = explode('.', $value);
            $lastPart = end($parts);
            if (count($parts) > 2 || strlen($lastPart) !== 2) {
                $value = str_replace('.', '', $value);
            }
            // else: biarkan sebagai desimal, contoh "1000000.50"
        }

        return preg_replace('/[^\d.]/', '', $value);
    }

    private function validateRow(array $row): array
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
        if (!empty($row['kondisi']) && !in_array($row['kondisi'], $allowedKondisi, true)) {
            $errors[] = 'Kondisi harus salah satu dari: ' . implode(', ', $allowedKondisi) . '.';
        }

        return $errors;
    }
}
