<?php

use App\Http\Controllers\Api\Library\DepartmentController;
use App\Http\Controllers\Api\Library\DivisionController;
use App\Http\Controllers\Api\Library\PositionController;
use App\Http\Controllers\Api\Library\DevelopmentTypeController;
use App\Http\Controllers\Api\Library\ManagingTypeController;
use App\Http\Controllers\Api\Library\DevelopmentSolutionTypeController;
use App\Http\Controllers\Api\Library\StakeholderTypeController;
use App\Http\Controllers\Api\Library\DocumentTypeController;
use App\Http\Controllers\Api\Library\RegistryStatusController;
use App\Http\Controllers\Api\Library\TechStackController;
use Illuminate\Support\Facades\Route;

// Require this file from within your existing Route::prefix('v1') group.
Route::group([], function () {
    // Public read-only endpoints for registration dropdowns.
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/departments', [DepartmentController::class, 'departments'])->name('library.departments');
        Route::get('/divisions', [DivisionController::class, 'divisions'])->name('library.divisions');
        Route::get('/positions', [PositionController::class, 'positions'])->name('library.positions');
        Route::get('/development-types', [DevelopmentTypeController::class, 'developmentTypeList'])->name('library.development-type.list');
        Route::get('/managing-types', [ManagingTypeController::class, 'managingTypeList'])->name('library.managing-type.list');
        Route::get('/development-solution-types', [DevelopmentSolutionTypeController::class, 'developmentSolutionTypeList'])->name('library.development-solution-type.list');
        Route::get('/stakeholder-types', [StakeholderTypeController::class, 'stakeholderTypeList'])->name('library.stakeholder-type.list');
        Route::get('/document-types', [DocumentTypeController::class, 'documentTypeList'])->name('library.document-type.list');
        Route::get('/registry-statuses', [RegistryStatusController::class, 'registryStatusList'])->name('library.registry-status.list');
        Route::get('/tech-stacks', [TechStackController::class, 'techStackList'])->name('library.tech-stack.list');
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

        Route::controller(DevelopmentTypeController::class)->group(function () {
            Route::post('/development-type-view', 'developmentTypeView')->name('library.development-type.view');
            Route::post('/development-type-create', 'developmentTypeCreate')->name('library.development-type.create');
            Route::post('/development-type-update', 'developmentTypeUpdate')->name('library.development-type.update');
            Route::post('/development-type-delete', 'developmentTypeDelete')->name('library.development-type.delete');
        });
        Route::controller(ManagingTypeController::class)->group(function () {
            Route::post('/managing-type-view', 'managingTypeView')->name('library.managing-type.view');
            Route::post('/managing-type-create', 'managingTypeCreate')->name('library.managing-type.create');
            Route::post('/managing-type-update', 'managingTypeUpdate')->name('library.managing-type.update');
            Route::post('/managing-type-delete', 'managingTypeDelete')->name('library.managing-type.delete');
        });

        Route::controller(DevelopmentSolutionTypeController::class)->group(function () {
            Route::post('/development-solution-type-view', 'developmentSolutionTypeView')->name('library.development-solution-type.view');
            Route::post('/development-solution-type-create', 'developmentSolutionTypeCreate')->name('library.development-solution-type.create');
            Route::post('/development-solution-type-update', 'developmentSolutionTypeUpdate')->name('library.development-solution-type.update');
            Route::post('/development-solution-type-delete', 'developmentSolutionTypeDelete')->name('library.development-solution-type.delete');
        });

        Route::controller(StakeholderTypeController::class)->group(function () {
            Route::post('/stakeholder-type-view', 'stakeholderTypeView')->name('library.stakeholder-type.view');
            Route::post('/stakeholder-type-create', 'stakeholderTypeCreate')->name('library.stakeholder-type.create');
            Route::post('/stakeholder-type-update', 'stakeholderTypeUpdate')->name('library.stakeholder-type.update');
            Route::post('/stakeholder-type-delete', 'stakeholderTypeDelete')->name('library.stakeholder-type.delete');
        });

        Route::controller(DocumentTypeController::class)->group(function () {
            Route::post('/document-type-view', 'documentTypeView')->name('library.document-type.view');
            Route::post('/document-type-create', 'documentTypeCreate')->name('library.document-type.create');
            Route::post('/document-type-update', 'documentTypeUpdate')->name('library.document-type.update');
            Route::post('/document-type-delete', 'documentTypeDelete')->name('library.document-type.delete');
        });

        Route::controller(RegistryStatusController::class)->group(function () {
            Route::post('/registry-status-view', 'registryStatusView')->name('library.registry-status.view');
            Route::post('/registry-status-create', 'registryStatusCreate')->name('library.registry-status.create');
            Route::post('/registry-status-update', 'registryStatusUpdate')->name('library.registry-status.update');
            Route::post('/registry-status-delete', 'registryStatusDelete')->name('library.registry-status.delete');
        });
        
        Route::controller(TechStackController::class)->group(function () {
            Route::post('/tech-stack-view', 'techStackView')->name('library.tech-stack.view');
            Route::post('/tech-stack-create', 'techStackCreate')->name('library.tech-stack.create');
            Route::post('/tech-stack-update', 'techStackUpdate')->name('library.tech-stack.update');
            Route::post('/tech-stack-delete', 'techStackDelete')->name('library.tech-stack.delete');
        });
    });
});
