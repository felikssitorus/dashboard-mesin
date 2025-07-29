<?php

use Illuminate\Support\Facades\Route;

// Admin Zone
Route::prefix('admin')->name('admin.')->middleware(['auth', 'CheckJobLvlPermission'])->group(function () {
    // permission role
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Permission\PermissionController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\System\Permission\PermissionController::class, 'getDataTablePermission'])->name('getDataTable');
        Route::get('create', [App\Http\Controllers\System\Permission\PermissionController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\Permission\PermissionController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\Permission\PermissionController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\Permission\PermissionController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Permission\PermissionController::class, 'destroy'])->name('destroy');
    });

    // permission line
    Route::prefix('permissionLine')->name('permissionLine.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'getDataTablePermission'])->name('getDataTable');
        Route::get('create', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'destroy'])->name('destroy');
    });

    // department
    Route::prefix('department')->name('dept.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Department\DepartmentController::class, 'index'])->name('index');
        Route::get('dt_dept', [App\Http\Controllers\System\Department\DepartmentController::class, 'dt_dept'])->name('dt_dept');
        Route::post('store', [App\Http\Controllers\System\Department\DepartmentController::class, 'store'])->name('store');
        Route::post('edit', [App\Http\Controllers\System\Department\DepartmentController::class, 'edit'])->name('edit');
        Route::post('update', [App\Http\Controllers\System\Department\DepartmentController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Department\DepartmentController::class, 'destroy'])->name('destroy');
    });

    // subdepartment
    Route::prefix('subdepartment')->name('subdept.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'index'])->name('index');
        Route::get('dt_subdept', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'dt_subdept'])->name('dt_subdept');
        Route::post('store', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'store'])->name('store');
        Route::post('edit', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'edit'])->name('edit');
        Route::post('update', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'destroy'])->name('destroy');
    });

    // Setting
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Settings\SettingsController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\System\Settings\SettingsController::class, 'store'])->name('store');
    });

    // User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\Admin\UserController::class, 'getDataTableUser'])->name('getDataTableUser');
        Route::get('create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
    });
});
