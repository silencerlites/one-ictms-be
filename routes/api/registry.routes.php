<?php

use App\Http\Controllers\Api\Registry\RegistryController;
use Illuminate\Support\Facades\Route;

// Include this once INSIDE the existing Route::prefix('v1') group.
// Add an administrator / ICTMS RBAC policy before production.
Route::middleware(['auth:api', 'throttle:60,1'])->controller(RegistryController::class)->group(function () {
    Route::get('/', 'index')->name('registry.index');
    Route::post('/create', 'create')->name('registry.create');
    Route::post('/view', 'view')->name('registry.view');
    Route::post('/update', 'update')->name('registry.update');
    Route::post('/delete', 'delete')->name('registry.delete');
});
