<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
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
