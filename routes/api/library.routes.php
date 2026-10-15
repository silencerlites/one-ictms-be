<?php

use App\Http\Controllers\Api\Library\DepartmentController;
use App\Http\Controllers\Api\Library\DivisionController;
use App\Http\Controllers\Api\Library\PositionController;
use Illuminate\Support\Facades\Route;

// Require this file from within your existing Route::prefix('v1') group.
Route::group([], function () {
    // Public read-only endpoints for registration dropdowns.
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/departments', [DepartmentController::class, 'departments'])->name('library.departments');
        Route::get('/divisions', [DivisionController::class, 'divisions'])->name('library.divisions');
        Route::get('/positions', [PositionController::class, 'positions'])->name('library.positions');
    });

    // Authentication required for detail and CRUD operations.
    // Apply your admin authorization middleware here before production.
    Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
        Route::controller(DepartmentController::class)->group(function () {
            Route::post('/department-view', 'departmentView')->name('library.department.view');
            Route::post('/department-create', 'departmentCreate')->name('library.department.create');
            Route::post('/department-update', 'departmentUpdate')->name('library.department.update');
            Route::post('/department-delete', 'departmentDelete')->name('library.department.delete');
        });

        Route::controller(DivisionController::class)->group(function () {
            Route::post('/division-view', 'divisionView')->name('library.division.view');
            Route::post('/division-create', 'divisionCreate')->name('library.division.create');
            Route::post('/division-update', 'divisionUpdate')->name('library.division.update');
            Route::post('/division-delete', 'divisionDelete')->name('library.division.delete');
        });

        Route::controller(PositionController::class)->group(function () {
            Route::post('/position-view', 'positionView')->name('library.position.view');
            Route::post('/position-create', 'positionCreate')->name('library.position.create');
            Route::post('/position-update', 'positionUpdate')->name('library.position.update');
            Route::post('/position-delete', 'positionDelete')->name('library.position.delete');
        });
    });
});
