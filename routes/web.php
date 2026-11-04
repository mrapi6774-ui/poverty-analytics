<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'timeSeries'])->name('time-series');
Route::get('/cross-section', [DashboardController::class, 'crossSection'])->name('cross-section');
Route::get('/apbd-analysis', [DashboardController::class, 'apbdAnalysis'])->name('apbd-analysis');
Route::get('/scatter-plot', [DashboardController::class, 'scatterPlot'])->name('scatter-plot');