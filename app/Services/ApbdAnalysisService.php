<?php

namespace App\Services;

use App\Models\ApbdExpenditure;
use App\Models\RegionalIndicatorData;
use App\Models\Indicator;
use App\Models\Sector;

class ApbdAnalysisService
{
    /**
     * Hitung Skor Relevansi Belanja
     * Rumus: Peringkat Kebutuhan Indikator x Peringkat Alokasi Anggaran
     */
    public function calculateRelevance($regencyId, $year)
    {
        $sectors = Sector::all();
        $results = [];

        foreach ($sectors as $sector) {
            // Total anggaran sektor
            $expenditure = ApbdExpenditure::where('regency_id', $regencyId)
                ->where('sector_id', $sector->id)
                ->where('year', $year)
                ->first();

            $totalBudget = $expenditure ? $expenditure->total_expense : 0;

            // Capaian indikator utama sektor ini
            $indicator = Indicator::where('sector_id', $sector->id)->first();
            $indicatorValue = 0;

            if ($indicator) {
                $indData = RegionalIndicatorData::where('regency_id', $regencyId)
                    ->where('indicator_id', $indicator->id)
                    ->where('year', $year)
                    ->first();
                $indicatorValue = $indData ? $indData->value : 0;
            }

            $results[] = [
                'sector_name' => $sector->name,
                'budget' => $totalBudget,
                'indicator_name' => $indicator ? $indicator->name : '-',
                'indicator_value' => $indicatorValue,
            ];
        }

        return $results;
    }

    /**
     * Hitung Rasio Efektivitas Belanja
     * Rumus: (Delta Indikator t - t-1) / (Delta Anggaran t - t-1)
     */
    public function calculateEffectiveness($regencyId, $year)
    {
        $sectors = Sector::all();
        $effectivenessData = [];
        $previousYear = $year - 1;

        foreach ($sectors as $sector) {
            // Anggaran tahun t & t-1
            $currentBudget = ApbdExpenditure::where('regency_id', $regencyId)
                ->where('sector_id', $sector->id)
                ->where('year', $year)
                ->value('total_expense') ?? 0;

            $prevBudget = ApbdExpenditure::where('regency_id', $regencyId)
                ->where('sector_id', $sector->id)
                ->where('year', $previousYear)
                ->value('total_expense') ?? 0;

            $deltaBudget = $currentBudget - $prevBudget;

            // Indikator tahun t & t-1
            $indicator = Indicator::where('sector_id', $sector->id)->first();
            $currentVal = 0;
            $prevVal = 0;

            if ($indicator) {
                $currentVal = RegionalIndicatorData::where('regency_id', $regencyId)
                    ->where('indicator_id', $indicator->id)
                    ->where('year', $year)
                    ->value('value') ?? 0;

                $prevVal = RegionalIndicatorData::where('regency_id', $regencyId)
                    ->where('indicator_id', $indicator->id)
                    ->where('year', $previousYear)
                    ->value('value') ?? 0;
            }

            $deltaIndicator = $currentVal - $prevVal;
            $ratio = $deltaBudget != 0 ? round(($deltaIndicator / $deltaBudget) * 100, 4) : 0;

            $effectivenessData[] = [
                'sector_name' => $sector->name,
                'delta_budget' => $deltaBudget,
                'delta_indicator' => $deltaIndicator,
                'effectiveness_ratio' => $ratio,
            ];
        }

        return $effectivenessData;
    }
}