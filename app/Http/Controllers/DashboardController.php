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
    $selectedRegency   = $request->get('regency_id', 3); // Default: Kab. Bangka
    $selectedIndicator = $request->get('indicator_id', 1); // Default: P0

    $regencies  = Regency::where('type', 'regency')->get();
    $sectors    = Sector::with('indicators')->get();
    $years      = range(2025, 2035);

    // 1. Data Trend Waktu Kabupaten Terpilih
    $kabData = RegionalIndicatorData::where('regency_id', $selectedRegency)
        ->where('indicator_id', $selectedIndicator)
        ->whereBetween('year', [2025, 2035])
        ->orderBy('year', 'asc')
        ->pluck('value', 'year');

    // 2. Data Trend Waktu Provinsi
    $provReg = Regency::where('type', 'province')->first();
    $provData = RegionalIndicatorData::where('regency_id', $provReg ? $provReg->id : null)
        ->where('indicator_id', $selectedIndicator)
        ->whereBetween('year', [2025, 2035])
        ->orderBy('year', 'asc')
        ->pluck('value', 'year');

    // 3. Data Trend Waktu Nasional
    $natReg = Regency::where('type', 'national')->first();
    $natData = RegionalIndicatorData::where('regency_id', $natReg ? $natReg->id : null)
        ->where('indicator_id', $selectedIndicator)
        ->whereBetween('year', [2025, 2035])
        ->orderBy('year', 'asc')
        ->pluck('value', 'year');

    // APBD Trend Waktu Kabupaten Terpilih
    $apbdData = ApbdExpenditure::where('regency_id', $selectedRegency)
        ->selectRaw('year, SUM(total_expense) as total_budget')
        ->groupBy('year')
        ->orderBy('year', 'asc')
        ->get();

    $indicatorObj  = Indicator::find($selectedIndicator);
    $selectedRegObj = Regency::find($selectedRegency);

    return view('pages.time-series', compact(
        'regencies', 'sectors', 'selectedRegency', 'selectedIndicator',
        'years', 'kabData', 'provData', 'natData', 'apbdData', 'indicatorObj', 'selectedRegObj'
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

    // 1. Data 7 Kabupaten/Kota pada Tahun Terpilih (Grafik Batang 1)
    $crossSectionData = RegionalIndicatorData::with('regency')
        ->whereHas('regency', function($q) {
            $q->where('type', 'regency');
        })
        ->where('indicator_id', $selectedIndicator)
        ->where('year', $selectedYear)
        ->orderBy('value', $sortOrder)
        ->get();

    // 2. Data Angka Acuan Provinsi & Nasional pada Tahun Terpilih (Badge Info)
    $provData = RegionalIndicatorData::whereHas('regency', fn($q) => $q->where('type', 'province'))
        ->where('indicator_id', $selectedIndicator)
        ->where('year', $selectedYear)
        ->first();

    $natData = RegionalIndicatorData::whereHas('regency', fn($q) => $q->where('type', 'national'))
        ->where('indicator_id', $selectedIndicator)
        ->where('year', $selectedYear)
        ->first();

    $provValue = $provData ? (float) $provData->value : 0;
    $natValue  = $natData ? (float) $natData->value : 0;

    // 3. Data Kurva Tren Waktu Provinsi & Nasional (2025-2035) untuk Grafik 2
    $provReg = Regency::where('type', 'province')->first();
    $natReg  = Regency::where('type', 'national')->first();

    $provTrend = RegionalIndicatorData::where('regency_id', $provReg ? $provReg->id : null)
        ->where('indicator_id', $selectedIndicator)
        ->whereBetween('year', [2025, 2035])
        ->orderBy('year', 'asc')
        ->pluck('value', 'year');

    $natTrend = RegionalIndicatorData::where('regency_id', $natReg ? $natReg->id : null)
        ->where('indicator_id', $selectedIndicator)
        ->whereBetween('year', [2025, 2035])
        ->orderBy('year', 'asc')
        ->pluck('value', 'year');

    return view('pages.cross-section', compact(
        'sectors', 'years', 'selectedIndicator',
        'selectedYear', 'crossSectionData', 'indicatorObj',
        'provValue', 'natValue', 'provTrend', 'natTrend'
    ));
}

    // 3. Halaman Analisis APBD & Efektivitas
    public function apbdAnalysis(Request $request)
{
    $selectedRegency = $request->get('regency_id', 3); // Default: Kab. Bangka
    $selectedYear    = $request->get('year', 2026);

    $regencies = Regency::whereIn('type', ['regency', 'province'])->get();
    $sectors   = Sector::all();
    $years     = range(2025, 2035);

    $selectedRegObj = Regency::find($selectedRegency);

    // 1. Data Tren Total APBD (2025–2035) untuk Wilayah Terpilih
    $apbdTrend = ApbdExpenditure::where('regency_id', $selectedRegency)
        ->selectRaw('year, SUM(total_expense) as total_budget')
        ->groupBy('year')
        ->orderBy('year', 'asc')
        ->pluck('total_budget', 'year');

    // 2. Data Komposisi Belanja per Sektor pada Tahun Terpilih
    $sectorComposition = ApbdExpenditure::with('sector')
        ->where('regency_id', $selectedRegency)
        ->where('year', $selectedYear)
        ->get();

    // 3. Perhitungan Analisis Efektivitas Sektor (Delta Indikator vs Delta Anggaran)
    // Ambil data P0 (Kemiskinan) tahun ini & tahun sebelumnya
    $p0Current = RegionalIndicatorData::where('regency_id', $selectedRegency)
        ->where('indicator_id', 1) // ID 1: Persentase Penduduk Miskin (P0)
        ->where('year', $selectedYear)
        ->value('value') ?? 0;

    $p0Prev = RegionalIndicatorData::where('regency_id', $selectedRegency)
        ->where('indicator_id', 1)
        ->where('year', $selectedYear - 1)
        ->value('value') ?? $p0Current;

    $deltaP0 = $p0Current - $p0Prev; // Perubahan angka kemiskinan

    // Hitung Rasio Efektivitas per Sektor
    $effectivenessData = [];
    foreach ($sectorComposition as $item) {
        $prevExpense = ApbdExpenditure::where('regency_id', $selectedRegency)
            ->where('sector_id', $item->sector_id)
            ->where('year', $selectedYear - 1)
            ->value('total_expense') ?? $item->total_expense;

        $deltaExpense = $item->total_expense - $prevExpense;

        // Status efektivitas: Jika kemiskinan turun (deltaP0 < 0) saat anggaran naik, maka Efektif
        $status = 'Netral';
        if ($deltaP0 < 0 && $deltaExpense > 0) {
            $status = 'Sangat Efektif';
        } elseif ($deltaP0 >= 0 && $deltaExpense > 0) {
            $status = 'Kurang Efektif';
        }

        $effectivenessData[] = [
            'sector_name'   => $item->sector->name,
            'current_exp'   => $item->total_expense,
            'delta_exp'     => $deltaExpense,
            'delta_p0'      => $deltaP0,
            'status'        => $status,
        ];
    }

    return view('pages.apbd-analysis', compact(
        'regencies', 'sectors', 'years', 'selectedRegency', 'selectedYear',
        'selectedRegObj', 'apbdTrend', 'sectorComposition', 'effectivenessData'
    ));
}

    // 4. Halaman Analisis Korelasi (Scatter Plot)
    public function scatterPlot(Request $request)
{
    $selectedIndX = $request->get('indicator_x', 2); // Default: Rata-rata Lama Sekolah / Indikator 2
    $selectedIndY = $request->get('indicator_y', 1); // Default: Persentase Penduduk Miskin (P0) / Indikator 1
    $selectedYear = $request->get('year', 2026);

    $sectors    = Sector::with('indicators')->get();
    $indicators = Indicator::all();
    $years      = range(2025, 2035);

    $indXObj = Indicator::find($selectedIndX);
    $indYObj = Indicator::find($selectedIndY);

    // Ambil data 7 Kabupaten/Kota untuk Variabel X & Y
    $regencies = Regency::where('type', 'regency')->get();

    $scatterPoints = [];
    $tableData     = [];

    foreach ($regencies as $reg) {
        $valX = RegionalIndicatorData::where('regency_id', $reg->id)
            ->where('indicator_id', $selectedIndX)
            ->where('year', $selectedYear)
            ->value('value') ?? 0;

        $valY = RegionalIndicatorData::where('regency_id', $reg->id)
            ->where('indicator_id', $selectedIndY)
            ->where('year', $selectedYear)
            ->value('value') ?? 0;

        $scatterPoints[] = [
            'x'     => (float) $valX,
            'y'     => (float) $valY,
            'label' => $reg->name,
        ];

        $tableData[] = [
            'regency_name' => $reg->name,
            'val_x'        => $valX,
            'val_y'        => $valY,
        ];
    }

    return view('pages.scatter-plot', compact(
        'sectors', 'indicators', 'years',
        'selectedIndX', 'selectedIndY', 'selectedYear',
        'indXObj', 'indYObj', 'scatterPoints', 'tableData'
    ));
}
}