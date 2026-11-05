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
    // Halaman Utama Pengelolaan Data (Dengan Filter & Pencarian)
public function index(Request $request)
{
    $regencies  = Regency::all();
    $indicators = Indicator::all();
    $sectors    = Sector::all();
    $years      = range(2025, 2035);

    // 1. Query Filter Data Indikator
    $indicatorQuery = RegionalIndicatorData::with(['regency', 'indicator']);

    if ($request->filled('search_regency')) {
        $indicatorQuery->where('regency_id', $request->search_regency);
    }
    if ($request->filled('search_indicator')) {
        $indicatorQuery->where('indicator_id', $request->search_indicator);
    }
    if ($request->filled('search_year')) {
        $indicatorQuery->where('year', $request->search_year);
    }

    $indicatorDataList = $indicatorQuery->orderBy('year', 'desc')
        ->orderBy('regency_id', 'asc')
        ->paginate(10, ['*'], 'indicator_page')
        ->withQueryString(); // Mempertahankan parameter filter saat berpindah halaman pagination

    // 2. Query Filter Data APBD
    $apbdQuery = ApbdExpenditure::with(['regency', 'sector']);

    if ($request->filled('search_apbd_regency')) {
        $apbdQuery->where('regency_id', $request->search_apbd_regency);
    }
    if ($request->filled('search_sector')) {
        $apbdQuery->where('sector_id', $request->search_sector);
    }
    if ($request->filled('search_apbd_year')) {
        $apbdQuery->where('year', $request->search_apbd_year);
    }

    $apbdDataList = $apbdQuery->orderBy('year', 'desc')
        ->orderBy('regency_id', 'asc')
        ->paginate(10, ['*'], 'apbd_page')
        ->withQueryString();

    return view('pages.data-management', compact(
        'regencies', 'indicators', 'sectors', 'years', 
        'indicatorDataList', 'apbdDataList'
    ));
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