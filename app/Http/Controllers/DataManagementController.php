<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regency;
use App\Models\Indicator;
use App\Models\Sector;
use App\Models\RegionalIndicatorData;
use App\Models\ApbdExpenditure;

class DataManagementController extends Controller
{
    // Halaman Utama Pengelolaan Data
    public function index(Request $request)
    {
        $regencies  = Regency::all();
        $indicators = Indicator::all();
        $sectors    = Sector::all();
        $years      = range(2025, 2035);

        // Data Indikator Daerah (Daftar & Pagination)
        $indicatorDataList = RegionalIndicatorData::with(['regency', 'indicator'])
            ->orderBy('year', 'desc')
            ->orderBy('regency_id', 'asc')
            ->paginate(10, ['*'], 'indicator_page');

        // Data APBD Sektor (Daftar & Pagination)
        $apbdDataList = ApbdExpenditure::with(['regency', 'sector'])
            ->orderBy('year', 'desc')
            ->orderBy('regency_id', 'asc')
            ->paginate(10, ['*'], 'apbd_page');

        return view('pages.data-management', compact(
            'regencies', 'indicators', 'sectors', 'years', 
            'indicatorDataList', 'apbdDataList'
        ));
    }

    // 1. Simpan / Update Data Indikator Manual
    public function storeIndicatorData(Request $request)
    {
        $request->validate([
            'regency_id'   => 'required|exists:master_regencies,id',
            'indicator_id' => 'required|exists:master_indicators,id',
            'year'         => 'required|integer|min:2025|max:2035',
            'value'        => 'required|numeric',
        ]);

        RegionalIndicatorData::updateOrCreate(
            [
                'regency_id'   => $request->regency_id,
                'indicator_id' => $request->indicator_id,
                'year'         => $request->year,
            ],
            [
                'value'        => $request->value,
            ]
        );

        return redirect()->back()->with('success_indicator', 'Data Indikator berhasil disimpan/diperbarui!');
    }

    // 2. Hapus Data Indikator
    public function destroyIndicatorData($id)
    {
        $data = RegionalIndicatorData::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success_indicator', 'Data Indikator berhasil dihapus!');
    }

    // 3. Simpan / Update Data Anggaran APBD
    public function storeApbdData(Request $request)
    {
        $request->validate([
            'regency_id'    => 'required|exists:master_regencies,id',
            'sector_id'     => 'required|exists:master_sectors,id',
            'year'          => 'required|integer|min:2025|max:2035',
            'total_expense' => 'required|numeric|min:0',
        ]);

        ApbdExpenditure::updateOrCreate(
            [
                'regency_id' => $request->regency_id,
                'sector_id'  => $request->sector_id,
                'year'       => $request->year,
            ],
            [
                'total_expense' => $request->total_expense,
            ]
        );

        return redirect()->back()->with('success_apbd', 'Data Anggaran APBD berhasil disimpan/diperbarui!');
    }

    // 4. Hapus Data APBD
    public function destroyApbdData($id)
    {
        $data = ApbdExpenditure::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success_apbd', 'Data APBD berhasil dihapus!');
    }
}