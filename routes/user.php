<?php

use Illuminate\Support\Facades\Route;


// untuk menghindari koneksi kalbe
Route::prefix('v1')->name('v1.')->middleware(['auth', 'MaintenanceMode', 'CheckJobLvlPermission'])->group(function () {
    Route::get('', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('getDataMesin', [App\Http\Controllers\DashboardController::class, 'getDataTableMesin'])->name('getDataTableMesin');
    });

    Route::prefix('auditTrail')->name('auditTrail.')->middleware(['auth'])->group(function () {
        Route::get('', [App\Http\Controllers\System\AuditController::class, 'index'])->name('index');
        Route::post('pdf', [App\Http\Controllers\System\AuditController::class, 'generatePdf'])->name('generatePdf');
    });

    Route::get('contactUs', [App\Http\Controllers\System\ContactUs\ContactUsController::class, 'index'])->name('contactUs');

    // data master line
    Route::prefix('line')->name('line.')->group(function () {
        Route::get('', [App\Http\Controllers\V1\LineController::class, 'index'])->name('index');
        Route::get('getDataTableLine', [App\Http\Controllers\V1\LineController::class, 'getDataTableLine'])->name('getDataTableLine');
        Route::post('store', [App\Http\Controllers\V1\LineController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\V1\LineController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [App\Http\Controllers\V1\LineController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\V1\LineController::class, 'destroy'])->name('destroy');
    });

    // data master proses
    Route::prefix('proses')->name('proses.')->group(function () {
        Route::get('', [App\Http\Controllers\V1\ProsesController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\V1\ProsesController::class, 'getDataTableProses'])->name('getDataTableProses');
        Route::post('store', [App\Http\Controllers\V1\ProsesController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\V1\ProsesController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [App\Http\Controllers\V1\ProsesController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\V1\ProsesController::class, 'destroy'])->name('destroy');
    });

    // data master mesin
    Route::prefix('mesin')->name('mesin.')->group(function () {
        Route::get('', [App\Http\Controllers\V1\MesinController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\V1\MesinController::class, 'getDataTableMesin'])->name('getDataTableMesin');
        Route::get('create', [App\Http\Controllers\V1\MesinController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\V1\MesinController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\V1\MesinController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [App\Http\Controllers\V1\MesinController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\V1\MesinController::class, 'destroy'])->name('destroy');
    });
    
});

// Route::prefix('v1')->name('v1.')->middleware(['auth', 'MaintenanceMode', 'CheckJobLvlPermission', 'NetworkTesting'])->group(function () {
//     Route::get('', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

//     Route::prefix('auditTrail')->name('auditTrail.')->middleware(['auth'])->group(function () {
//         Route::get('', [App\Http\Controllers\System\AuditController::class, 'index'])->name('index');
//         Route::post('pdf', [App\Http\Controllers\System\AuditController::class, 'generatePdf'])->name('generatePdf');
//     });

//     Route::get('contactUs', [App\Http\Controllers\System\ContactUs\ContactUsController::class, 'index'])->name('contactUs');

//     // data master line
//     Route::prefix('line')->name('line.')->group(function () {
//         Route::get('', [App\Http\Controllers\V1\LineController::class, 'index'])->name('index');
//         Route::get('getData', [App\Http\Controllers\V1\LineController::class, 'getDataTableLine'])->name('getDataTableLine');
//         Route::post('store', [App\Http\Controllers\V1\LineController::class, 'store'])->name('store');
//         Route::get('edit/{id}', [App\Http\Controllers\V1\LineController::class, 'edit'])->name('edit');
//         Route::put('update/{id}', [App\Http\Controllers\V1\LineController::class, 'update'])->name('update');
//         Route::post('destroy', [App\Http\Controllers\V1\LineController::class, 'destroy'])->name('destroy');
//     });

//     // data master proses
//     Route::prefix('proses')->name('proses.')->group(function () {
//         Route::get('', [App\Http\Controllers\V1\ProsesController::class, 'index'])->name('index');
//         Route::get('getData', [App\Http\Controllers\V1\ProsesController::class, 'getDataTableProses'])->name('getDataTableProses');
//         Route::post('store', [App\Http\Controllers\V1\ProsesController::class, 'store'])->name('store');
//         Route::get('edit/{id}', [App\Http\Controllers\V1\ProsesController::class, 'edit'])->name('edit');
//         Route::put('update/{id}', [App\Http\Controllers\V1\ProsesController::class, 'update'])->name('update');
//         Route::post('destroy', [App\Http\Controllers\V1\ProsesController::class, 'destroy'])->name('destroy');
//     });

//     // data master mesin
//     Route::prefix('mesin')->name('mesin.')->group(function () {
//         Route::get('getData', [App\Http\Controllers\V1\MesinController::class, 'getDataTableMesin'])->name('getDataTableMesin');
//         Route::get('create', [App\Http\Controllers\V1\MesinController::class, 'create'])->name('create');
//         Route::post('store', [App\Http\Controllers\V1\MesinController::class, 'store'])->name('store');
//         Route::get('edit/{id}', [App\Http\Controllers\V1\MesinController::class, 'edit'])->name('edit');
//         Route::put('update/{id}', [App\Http\Controllers\V1\MesinController::class, 'update'])->name('update');
//         Route::post('destroy', [App\Http\Controllers\V1\MesinController::class, 'destroy'])->name('destroy');
//     });
// });
