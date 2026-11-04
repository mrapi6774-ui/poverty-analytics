<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Regency;
use App\Models\Indicator;
use App\Models\Sector;
use App\Models\RegionalIndicatorData;
use App\Models\ApbdExpenditure;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $regencies  = Regency::all();
        $indicators = Indicator::all();
        $sectors    = Sector::all();
        $years      = range(2025, 2035);

        // Baseline angka indikator acuan
        $baselines = [
            'P0'        => 4.80,   // % Penduduk Miskin
            'GK'        => 780000, // Garis Kemiskinan (Rp)
            'P1'        => 0.75,   // Kedalaman Kemiskinan
            'P2'        => 0.18,   // Keparahan Kemiskinan
            'HLS'       => 13.20,  // Harapan Lama Sekolah
            'RLS'       => 8.80,   // Rata-rata Lama Sekolah
            'APM'       => 92.50,  // APM (%)
            'AHH'       => 71.50,  // Angka Harapan Hidup
            'AKB'       => 12.40,  // Kematian Bayi per 1000 KH
            'STUNT'     => 18.50,  // Stunting (%)
            'SAN_LAYAK' => 88.00,  // Sanitasi Layak (%)
            'AIR_LAYAK' => 91.20,  // Air Minum Layak (%)
            'TPT'       => 4.60,   // Pengangguran Terbuka (%)
            'LPE'       => 5.10,   // Pertumbuhan Ekonomi (%)
        ];

        // 1. Generate Data Indikator Daerah (2025-2035)
        foreach ($regencies as $regIndex => $regency) {
            foreach ($indicators as $indicator) {
                $base = $baselines[$indicator->code] ?? 10.0;

                foreach ($years as $yearIndex => $year) {
                    // Trend tahunan sederhana + variasi antar wilayah
                    $variance = ($regIndex * 0.15) * ($indicator->direction === 'positive' ? 1 : -1);
                    $annualDelta = $yearIndex * ($indicator->direction === 'positive' ? 0.25 : -0.15);

                    // Tambahan variasi acak acuan
                    if ($indicator->code === 'GK') {
                        $val = round($base + ($yearIndex * 35000) + ($regIndex * 15000), 2);
                    } else {
                        $val = round(max(0, $base + $variance + $annualDelta), 2);
                    }

                    RegionalIndicatorData::updateOrCreate(
                        [
                            'regency_id'   => $regency->id,
                            'indicator_id' => $indicator->id,
                            'year'         => $year,
                        ],
                        [
                            'value' => $val,
                        ]
                    );
                }
            }
        }

        // 2. Generate Data Realisasi APBD per Sektor (2025-2035) dalam Jutaan Rp
        foreach ($regencies as $regIndex => $regency) {
            foreach ($sectors as $secIndex => $sector) {
                $baseBudget = 150000 + ($secIndex * 40000) + ($regIndex * 25000); // Baseline jutaan rupiah

                foreach ($years as $yearIndex => $year) {
                    $growthFactor = 1 + ($yearIndex * 0.05); // Kenaikan anggaran ~5% per tahun
                    $direct   = round(($baseBudget * 0.65) * $growthFactor, 2);
                    $indirect = round(($baseBudget * 0.35) * $growthFactor, 2);
                    $total    = $direct + $indirect;

                    ApbdExpenditure::updateOrCreate(
                        [
                            'regency_id' => $regency->id,
                            'sector_id'  => $sector->id,
                            'year'       => $year,
                        ],
                        [
                            'direct_expense'   => $direct,
                            'indirect_expense' => $indirect,
                            'total_expense'    => $total,
                        ]
                    );
                }
            }
        }
    }
}