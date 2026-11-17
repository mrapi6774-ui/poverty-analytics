<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApbdController;
use App\Http\Controllers\ExcelController;

/*
|--------------------------------------------------------------------------
| 1. PORTAL PUBLIK (Visualisasi & Laporan — Tanpa Menu Admin)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'timeSeries'])->name('time-series');
Route::get('/cross-section', [DashboardController::class, 'crossSection'])->name('cross-section');
Route::get('/scatter-plot', [DashboardController::class, 'scatterPlot'])->name('scatter-plot');
Route::get('/apbd-analysis', [ApbdController::class, 'index'])->name('apbd-analysis');
Route::get('/report/pdf', [DashboardController::class, 'reportPDF'])->name('report.pdf');

/*
|--------------------------------------------------------------------------
| 2. PORTAL ADMIN TERPISAH (/admin/...)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Redirect /admin otomatis ke halaman login atau kelola data
    Route::get('/', function () {
        return Auth::check()
            ? redirect()->route('data-management')
            : redirect()->route('login');
    })->name('admin.index');

    // Autentikasi Khusus Admin (/admin/login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Halaman & Aksi Pengelolaan Penuh (Wajib Login Admin)
    Route::middleware('auth')->group(function () {
        // A. Kelola Data Tahunan & Integrasi Excel (/admin/data-management)
        Route::get('/data-management', [DataManagementController::class, 'index'])->name('data-management');
        Route::post('/data-management/indicator', [DataManagementController::class, 'storeIndicatorData'])->name('data-management.store-indicator');
        Route::delete('/data-management/indicator/{id}', [DataManagementController::class, 'destroyIndicatorData'])->name('data-management.destroy-indicator');

        Route::post('/data-management/apbd', [DataManagementController::class, 'storeApbdData'])->name('data-management.store-apbd');
        Route::delete('/data-management/apbd/{id}', [DataManagementController::class, 'destroyApbdData'])->name('data-management.destroy-apbd');

        Route::post('/data-management/program', [DataManagementController::class, 'storeProgramData'])->name('data-management.store-program');
        Route::delete('/data-management/program/{id}', [DataManagementController::class, 'destroyProgramData'])->name('data-management.destroy-program');

        Route::post('/data-management/account', [DataManagementController::class, 'storeAccountRecord'])->name('data-management.store-account');
        Route::delete('/data-management/account/{id}', [DataManagementController::class, 'destroyAccountRecord'])->name('data-management.destroy-account');

        // Unduh Template & Impor Excel (.xlsx)
        Route::get('/data-management/excel/template', [ExcelController::class, 'downloadTemplate'])->name('data-management.excel.template');
        Route::post('/data-management/excel/import', [ExcelController::class, 'import'])->name('data-management.excel.import');

        // B. Kelola Data Master Referensi Publik (/admin/master-data)
        Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data');
        Route::post('/master-data/regency', [MasterDataController::class, 'storeRegency'])->name('master-data.store-regency');
        Route::delete('/master-data/regency/{id}', [MasterDataController::class, 'destroyRegency'])->name('master-data.destroy-regency');

        Route::post('/master-data/indicator', [MasterDataController::class, 'storeIndicator'])->name('master-data.store-indicator');
        Route::delete('/master-data/indicator/{id}', [MasterDataController::class, 'destroyIndicator'])->name('master-data.destroy-indicator');

        Route::post('/master-data/sector', [MasterDataController::class, 'storeSector'])->name('master-data.store-sector');
        Route::delete('/master-data/sector/{id}', [MasterDataController::class, 'destroySector'])->name('master-data.destroy-sector');

        Route::post('/master-data/program', [MasterDataController::class, 'storeProgram'])->name('master-data.store-program');
        Route::delete('/master-data/program/{id}', [MasterDataController::class, 'destroyProgram'])->name('master-data.destroy-program');

        Route::post('/master-data/account', [MasterDataController::class, 'storeAccount'])->name('master-data.store-account');
        Route::delete('/master-data/account/{id}', [MasterDataController::class, 'destroyAccount'])->name('master-data.destroy-account');
    });
});