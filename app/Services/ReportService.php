<?php

namespace App\Services;

use App\Models\AssetModel;
use App\Models\RepairHistoryModel;

class ReportService
{
    public function __construct(
        private AssetModel $assetModel,
        private RepairHistoryModel $repairModel
    ) {}

    private const ALLOWED_KONDISI = ['baik', 'rusak', 'dalam_perbaikan', 'tidak_aktif'];

    public function getSummary(): array
    {
        $rawStats = $this->assetModel
            ->select('kondisi, COUNT(*) as total')
            ->groupBy('kondisi')
            ->findAll();

        $countByKondisi = array_column($rawStats, 'total', 'kondisi');

        $kondisiStats = array_map(
            fn(string $kondisi) => [
                'kondisi' => $kondisi,
                'total'   => (int) ($countByKondisi[$kondisi] ?? 0),
            ],
            self::ALLOWED_KONDISI
        );

        $totalBiaya = $this->repairModel
            ->selectSum('biaya')
            ->first();

        return [
            'kondisi_stats'         => $kondisiStats,
            'total_biaya_perbaikan' => (float) ($totalBiaya['biaya'] ?? 0),
            'total_aset'            => $this->assetModel->countAllResults(),
            'generated_at'          => date('Y-m-d H:i:s'),
        ];
    }
}
