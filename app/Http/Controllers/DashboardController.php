<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sector;
use App\Models\Indicator;
use App\Models\Regency;
use App\Models\RegionalIndicatorData;
use App\Models\ApbdExpenditure;

class DashboardController extends Controller
{
    // 1. Halaman Trend Waktu (Time Series)
    public function timeSeries(Request $request)
    {
        $selectedRegency   = $request->get('regency_id', 1);
        $selectedIndicator = $request->get('indicator_id', 1);

        $regencies  = Regency::all();
        $sectors    = Sector::with('indicators')->get();

        $timeSeriesData = RegionalIndicatorData::where('regency_id', $selectedRegency)
            ->where('indicator_id', $selectedIndicator)
            ->whereBetween('year', [2025, 2035])
            ->orderBy('year', 'asc')
            ->get();

        $apbdData = ApbdExpenditure::where('regency_id', $selectedRegency)
            ->selectRaw('year, SUM(total_expense) as total_budget')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $indicatorObj = Indicator::find($selectedIndicator);

        return view('pages.time-series', compact(
            'regencies', 'sectors', 'selectedRegency',
            'selectedIndicator', 'timeSeriesData', 'apbdData', 'indicatorObj'
        ));
    }

    // 2. Halaman Analisis Antar Wilayah (Cross Section)
    public function crossSection(Request $request)
    {
        $selectedIndicator = $request->get('indicator_id', 1);
        $selectedYear      = $request->get('year', 2026);

        $sectors    = Sector::with('indicators')->get();
        $years      = range(2025, 2035);

        $indicatorObj = Indicator::find($selectedIndicator);
        $sortOrder    = ($indicatorObj && $indicatorObj->direction === 'positive') ? 'desc' : 'asc';

        $crossSectionData = RegionalIndicatorData::with('regency')
            ->where('indicator_id', $selectedIndicator)
            ->where('year', $selectedYear)
            ->orderBy('value', $sortOrder)
            ->get();

        return view('pages.cross-section', compact(
            'sectors', 'years', 'selectedIndicator',
            'selectedYear', 'crossSectionData', 'indicatorObj'
        ));
    }

    // 3. Halaman Analisis APBD & Efektivitas
    public function apbdAnalysis(Request $request)
    {
        $selectedRegency   = $request->get('regency_id', 1);
        $selectedIndicator = $request->get('indicator_id', 1);

        $regencies  = Regency::all();
        $sectors    = Sector::with('indicators')->get();
        $years      = range(2025, 2035);

        $indicatorObj = Indicator::find($selectedIndicator);
        $sectorIdOfSelectedIndicator = $indicatorObj ? $indicatorObj->sector_id : 1;

        // Data Stacked Bar Chart Sektor
        $apbdSectorBreakdown = ApbdExpenditure::with('sector')
            ->where('regency_id', $selectedRegency)
            ->whereBetween('year', [2025, 2035])
            ->get()
            ->groupBy('sector.name');

        $stackedDatasets = [];
        $colors = ['#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#6f42c1'];
        $colorIndex = 0;

        foreach ($apbdSectorBreakdown as $sectorName => $expenditures) {
            $budgetByYear = $expenditures->pluck('total_expense', 'year')->toArray();
            $dataPoints = [];
            foreach ($years as $y) {
                $dataPoints[] = $budgetByYear[$y] ?? 0;
            }
            $stackedDatasets[] = [
                'label'           => $sectorName ?: 'Lainnya',
                'data'            => $dataPoints,
                'backgroundColor' => $colors[$colorIndex % count($colors)],
            ];
            $colorIndex++;
        }

        // Tabel Efektivitas
        $timeSeriesData = RegionalIndicatorData::where('regency_id', $selectedRegency)
            ->where('indicator_id', $selectedIndicator)
            ->whereBetween('year', [2025, 2035])
            ->orderBy('year', 'asc')
            ->get();

        $tsKeyByYear = $timeSeriesData->keyBy('year');

        $sectorBudgetsByYear = ApbdExpenditure::where('regency_id', $selectedRegency)
            ->where('sector_id', $sectorIdOfSelectedIndicator)
            ->whereBetween('year', [2025, 2035])
            ->get()
            ->pluck('total_expense', 'year');

        $effectivityTable = [];
        for ($i = 1; $i < count($years); $i++) {
            $prevYear = $years[$i - 1];
            $currYear = $years[$i];

            $valPrev = isset($tsKeyByYear[$prevYear]) ? $tsKeyByYear[$prevYear]->value : 0;
            $valCurr = isset($tsKeyByYear[$currYear]) ? $tsKeyByYear[$currYear]->value : 0;
            $deltaVal = $valCurr - $valPrev;

            $budgetPrev = $sectorBudgetsByYear[$prevYear] ?? 0;
            $budgetCurr = $sectorBudgetsByYear[$currYear] ?? 0;
            $deltaBudget = $budgetCurr - $budgetPrev;

            $ratio = ($deltaBudget != 0) ? ($deltaVal / ($deltaBudget / 10000)) : 0;
            $isPositiveTrend = ($indicatorObj && $indicatorObj->direction === 'positive') ? ($deltaVal > 0) : ($deltaVal < 0);

            $effectivityTable[] = [
                'year'         => $currYear,
                'val_curr'     => $valCurr,
                'delta_val'    => $deltaVal,
                'budget_curr'  => $budgetCurr,
                'delta_budget' => $deltaBudget,
                'ratio'        => round($ratio, 4),
                'status'       => $isPositiveTrend ? 'Efektif' : 'Kurang Efektif'
            ];
        }

        return view('pages.apbd-analysis', compact(
            'regencies', 'sectors', 'years', 'selectedRegency',
            'selectedIndicator', 'stackedDatasets', 'effectivityTable', 'indicatorObj'
        ));
    }

    // 4. Halaman Analisis Korelasi (Scatter Plot)
    public function scatterPlot(Request $request)
    {
        $selectedYear       = $request->get('year', 2026);
        $selectedIndicatorX = $request->get('indicator_x', 6);
        $selectedIndicatorY = $request->get('indicator_y', 1);

        $indicators = Indicator::all();
        $years      = range(2025, 2035);
        $regencies  = Regency::all();

        $dataX = RegionalIndicatorData::where('indicator_id', $selectedIndicatorX)
            ->where('year', $selectedYear)
            ->get()
            ->keyBy('regency_id');

        $dataY = RegionalIndicatorData::where('indicator_id', $selectedIndicatorY)
            ->where('year', $selectedYear)
            ->get()
            ->keyBy('regency_id');

        $scatterPoints = [];
        foreach ($regencies as $reg) {
            if ($reg->type === 'regency' && isset($dataX[$reg->id]) && isset($dataY[$reg->id])) {
                $scatterPoints[] = [
                    'regency' => $reg->name,
                    'x'       => (float) $dataX[$reg->id]->value,
                    'y'       => (float) $dataY[$reg->id]->value,
                ];
            }
        }

        $indicatorXObj = Indicator::find($selectedIndicatorX);
        $indicatorYObj = Indicator::find($selectedIndicatorY);

        return view('pages.scatter-plot', compact(
            'indicators', 'years', 'selectedYear', 'selectedIndicatorX',
            'selectedIndicatorY', 'scatterPoints', 'indicatorXObj', 'indicatorYObj'
        ));
    }
}