<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| ROUTE AUTENTIKASI (Khusus Guest / Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route Logout (Dapat dipanggil saat user sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTE VISUALISASI DASHBOARD & REPORT (Dapat Diakses Publik / Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'timeSeries'])->name('time-series');
Route::get('/cross-section', [DashboardController::class, 'crossSection'])->name('cross-section');
Route::get('/apbd-analysis', [DashboardController::class, 'apbdAnalysis'])->name('apbd-analysis');
Route::get('/scatter-plot', [DashboardController::class, 'scatterPlot'])->name('scatter-plot');
Route::get('/report/pdf', [DashboardController::class, 'reportPDF'])->name('report.pdf');

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

    // Route Action CRUD APBD
    Route::post('/data-management/apbd', [DataManagementController::class, 'storeApbdData'])->name('data-management.store-apbd');
    Route::delete('/data-management/apbd/{id}', [DataManagementController::class, 'destroyApbdData'])->name('data-management.destroy-apbd');
});