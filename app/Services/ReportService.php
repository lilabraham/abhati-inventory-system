<?php

namespace App\Services;

use App\Models\AssetModel;
use App\Models\RepairHistoryModel;

class ReportService
{
    protected $assetModel;
    protected $repairModel;

    public function __construct()
    {
        $this->assetModel  = new AssetModel();
        $this->repairModel = new RepairHistoryModel();
    }

    public function getSummary(int $totalAset): array
    {
        $kondisiStats = $this->assetModel
            ->select('kondisi, COUNT(*) as total')
            ->groupBy('kondisi')
            ->findAll();

        $totalBiaya = $this->repairModel
            ->selectSum('biaya')
            ->first();

        $totalBiaya = $totalBiaya['biaya'] ?? 0;

        return [
            'kondisi_stats'         => $kondisiStats,
            'total_biaya_perbaikan' => (float) $totalBiaya,
            'total_aset'            => $totalAset,
            'generated_at'          => date('Y-m-d H:i:s'),
        ];
    }
}