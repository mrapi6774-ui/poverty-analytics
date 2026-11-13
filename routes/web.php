<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApbdController;

/*
|--------------------------------------------------------------------------
| ROUTE AUTENTIKASI (Khusus Guest / Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTE VISUALISASI DASHBOARD & REPORT (Dapat Diakses Publik / Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'timeSeries'])->name('time-series');
Route::get('/cross-section', [DashboardController::class, 'crossSection'])->name('cross-section');
Route::get('/scatter-plot', [DashboardController::class, 'scatterPlot'])->name('scatter-plot');
Route::get('/report/pdf', [DashboardController::class, 'reportPDF'])->name('report.pdf');
Route::get('/apbd-analysis', [ApbdController::class, 'index'])->name('apbd-analysis');

/*
|--------------------------------------------------------------------------
| ROUTE KELOLA DATA / CRUD (Wajib Login Sebagai Admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Route Halaman Kelola Data
    Route::get('/data-management', [DataManagementController::class, 'index'])->name('data-management');

    // Route Action CRUD Indikator
    Route::post('/data-management/indicator', [DataManagementController::class, 'storeIndicatorData'])->name('data-management.store-indicator');
    Route::delete('/data-management/indicator/{id}', [DataManagementController::class, 'destroyIndicatorData'])->name('data-management.destroy-indicator');

    // Route Action CRUD APBD (Total per Sektor)
    Route::post('/data-management/apbd', [DataManagementController::class, 'storeApbdData'])->name('data-management.store-apbd');
    Route::delete('/data-management/apbd/{id}', [DataManagementController::class, 'destroyApbdData'])->name('data-management.destroy-apbd');

    // Route Action CRUD Rincian Program & Sub-Kegiatan APBD
    Route::post('/data-management/program', [DataManagementController::class, 'storeProgramData'])->name('data-management.store-program');
    Route::delete('/data-management/program/{id}', [DataManagementController::class, 'destroyProgramData'])->name('data-management.destroy-program');

    // Route Action CRUD Akun Ringkasan APBD (Pendapatan 4, Belanja Tidak Langsung 5.1, Pembiayaan 6)
    Route::post('/data-management/account', [DataManagementController::class, 'storeAccountRecord'])->name('data-management.store-account');
    Route::delete('/data-management/account/{id}', [DataManagementController::class, 'destroyAccountRecord'])->name('data-management.destroy-account');
});