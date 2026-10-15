
<?php

use Illuminate\Support\Facades\Route;


Route::get('/health', function () {return response()->json(['success' => true, 'message' => 'API is running.']);});
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->middleware('web')->group(base_path('routes/api/auth.routes.php'));
    Route::prefix('library')->group(base_path('routes/api/library.routes.php'));
});
