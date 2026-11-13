<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regency;
use App\Models\Sector;
use App\Models\ApbdExpenditure;
use App\Models\RegionalIndicatorData;
use App\Models\Indicator;
use App\Models\MasterProgram;
use App\Models\MasterApbdAccount;
use App\Models\ApbdAccountRecord;

class ApbdController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Parameter Filter
        $selectedRegency = $request->input('regency_id', 3);
        $selectedYear    = $request->input('year', 2026);

        $regencies      = Regency::where('type', 'regency')->get();
        $selectedRegObj = Regency::find($selectedRegency);
        $years          = range(2025, 2035);

        // 2. Data Tren Belanja APBD (2025–2035)
        $apbdTrend = ApbdExpenditure::where('regency_id', $selectedRegency)
            ->selectRaw('year, SUM(total_expense) as total_expense')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total_expense', 'year');

        // 3. Data Komposisi Belanja Langsung per Sektor (5.2)
        $sectorComposition = ApbdExpenditure::with('sector')
            ->where('regency_id', $selectedRegency)
            ->where('year', $selectedYear)
            ->get();

        // 4. Data Matriks Relevansi Belanja ($relevanceData)
        $sectors = Sector::all();
        $relevanceData = [];
        foreach ($sectors as $sector) {
            $budget = ApbdExpenditure::where('regency_id', $selectedRegency)
                ->where('sector_id', $sector->id)
                ->where('year', $selectedYear)
                ->value('total_expense') ?? 0;

            $indicator = Indicator::where('sector_id', $sector->id)->first();
            $indicatorVal = 0;
            if ($indicator) {
                $indicatorVal = RegionalIndicatorData::where('regency_id', $selectedRegency)
                    ->where('indicator_id', $indicator->id)
                    ->where('year', $selectedYear)
                    ->value('value') ?? 0;
            }

            $relevanceData[] = [
                'sector_name'     => $sector->name,
                'budget'          => $budget,
                'indicator_name'  => $indicator ? $indicator->name : '-',
                'indicator_value' => $indicatorVal,
            ];
        }

        // 5. Data Efektivitas Alokasi Anggaran ($effectivenessData)
        $effectivenessData = [];
        $prevYear = $selectedYear - 1;

        foreach ($sectorComposition as $item) {
            $prevExp = ApbdExpenditure::where('regency_id', $selectedRegency)
                ->where('sector_id', $item->sector_id)
                ->where('year', $prevYear)
                ->value('total_expense') ?? 0;

            $deltaExp = $item->total_expense - $prevExp;

            $p0Ind  = Indicator::where('code', 'P0')->first();
            $currP0 = 0;
            $prevP0 = 0;
            if ($p0Ind) {
                $currP0 = RegionalIndicatorData::where('regency_id', $selectedRegency)
                    ->where('indicator_id', $p0Ind->id)
                    ->where('year', $selectedYear)
                    ->value('value') ?? 0;
                $prevP0 = RegionalIndicatorData::where('regency_id', $selectedRegency)
                    ->where('indicator_id', $p0Ind->id)
                    ->where('year', $prevYear)
                    ->value('value') ?? 0;
            }
            $deltaP0 = $currP0 - $prevP0;

            $status = ($deltaExp > 0 && $deltaP0 < 0) ? 'Sangat Efektif' : (($deltaP0 >= 0) ? 'Kurang Efektif' : 'Netral');

            $effectivenessData[] = [
                'sector_name' => $item->sector->name,
                'current_exp' => $item->total_expense,
                'delta_exp'   => $deltaExp,
                'delta_p0'    => $deltaP0,
                'status'      => $status,
            ];
        }

        // 6. Data Rincian Program (Level 1) & Sub-Kegiatan (Level 2) per Sektor
        $programsBySector = MasterProgram::with([
            'children' => function ($query) use ($selectedRegency, $selectedYear) {
                $query->orderBy('code')->with(['expenditures' => function ($q) use ($selectedRegency, $selectedYear) {
                    $q->where('regency_id', $selectedRegency)->where('year', $selectedYear);
                }]);
            },
            'expenditures' => function ($q) use ($selectedRegency, $selectedYear) {
                $q->where('regency_id', $selectedRegency)->where('year', $selectedYear);
            }
        ])
        ->where('level', 1)
        ->orderBy('code')
        ->get()
        ->groupBy('sector_id');

        // 7. Data Ringkasan APBD (Pendapatan 4, Belanja Tidak Langsung 5.1, Pembiayaan 6)
        $allAccounts = MasterApbdAccount::with(['records' => function ($q) use ($selectedRegency, $selectedYear) {
            $q->where('regency_id', $selectedRegency)->where('year', $selectedYear);
        }])->orderBy('id')->get();

        $pendapatanAccounts   = $allAccounts->where('type', 'pendapatan');
        $belanja51Accounts    = $allAccounts->where('type', 'belanja_tidak_langsung');
        $pembiayaanAccounts   = $allAccounts->where('type', 'pembiayaan');

        $totalPendapatan      = $pendapatanAccounts->sum(fn($a) => $a->records->first()->amount ?? 0);
        $totalBelanja51       = $belanja51Accounts->sum(fn($a) => $a->records->first()->amount ?? 0);
        $totalBelanja52       = $sectorComposition->sum('total_expense');
        $totalSemuaBelanja    = $totalBelanja51 + $totalBelanja52;
        $surplusDefisit       = $totalPendapatan - $totalSemuaBelanja;

        $penerimaanPembiayaan  = $pembiayaanAccounts->where('category_code', '6.1')->sum(fn($a) => $a->records->first()->amount ?? 0);
        $pengeluaranPembiayaan = $pembiayaanAccounts->where('category_code', '6.2')->sum(fn($a) => $a->records->first()->amount ?? 0);
        $pembiayaanNeto        = $penerimaanPembiayaan - $pengeluaranPembiayaan;

        return view('pages.apbd-analysis', compact(
            'regencies',
            'selectedRegency',
            'years',
            'selectedYear',
            'sectorComposition',
            'selectedRegObj',
            'apbdTrend',
            'relevanceData',
            'effectivenessData',
            'sectors',
            'programsBySector',
            'pendapatanAccounts',
            'belanja51Accounts',
            'pembiayaanAccounts',
            'totalPendapatan',
            'totalBelanja51',
            'totalBelanja52',
            'totalSemuaBelanja',
            'surplusDefisit',
            'pembiayaanNeto'
        ));
    }
}