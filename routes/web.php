<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributionLookupController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportEvidenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'can:manage-master-data'])->group(function () {
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data.index');

    Route::post('/schools', [MasterDataController::class, 'storeSchool'])->name('schools.store');
    Route::get('/schools/{school}/edit', [MasterDataController::class, 'editSchool'])->name('schools.edit');
    Route::patch('/schools/{school}', [MasterDataController::class, 'updateSchool'])->name('schools.update');
    Route::delete('/schools/{school}', [MasterDataController::class, 'destroySchool'])->name('schools.destroy');

    Route::post('/distributions', [MasterDataController::class, 'storeDistribution'])->name('distributions.store');
    Route::get('/distributions/{distribution}/edit', [MasterDataController::class, 'editDistribution'])->name('distributions.edit');
    Route::patch('/distributions/{distribution}', [MasterDataController::class, 'updateDistribution'])->name('distributions.update');
    Route::delete('/distributions/{distribution}', [MasterDataController::class, 'destroyDistribution'])->name('distributions.destroy');
});

Route::middleware(['auth', 'can:create-report'])->group(function () {
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/schools/{school}/distributions', [DistributionLookupController::class, 'index'])
        ->name('schools.distributions.index');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])
        ->whereNumber('report')
        ->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])
        ->whereNumber('report')
        ->name('reports.edit');
    Route::patch('/reports/{report}', [ReportController::class, 'update'])
        ->whereNumber('report')
        ->name('reports.update');
    Route::post('/reports/{report}/review', [ReportController::class, 'review'])
        ->whereNumber('report')
        ->name('reports.review');
    Route::post('/reports/{report}/resubmit', [ReportController::class, 'resubmit'])
        ->whereNumber('report')
        ->name('reports.resubmit');
    Route::post('/reports/{report}/referral', [ReportController::class, 'refer'])
        ->whereNumber('report')
        ->name('reports.referral');
    Route::get('/reports/{report}/evidence', [ReportEvidenceController::class, 'show'])
        ->whereNumber('report')
        ->name('reports.evidence');
});
