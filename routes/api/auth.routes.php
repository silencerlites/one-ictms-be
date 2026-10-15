
<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Middleware\EnsureTokenVersion;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {

    Route::get('csrf', function () {return response()->json(['success' => true, 'message' => 'CSRF initialized successfully.']);});
    Route::post('register', 'register')->middleware('throttle:5,1')->name('auth.register');
    Route::post('login', 'login')->middleware('throttle:10,1')->name('auth.login');
    Route::post('verify-login-otp', 'verifyLoginOtp')->middleware('throttle:10,1')->name('auth.verify-login-otp');
    Route::post('resend-login-otp', 'resendLoginOtp')->middleware('throttle:5,1')->name('auth.resend-login-otp');
    Route::post('verify-email', 'verifyEmail')->middleware('throttle:10,1')->name('auth.verify-email');
    Route::post('resend-verification', 'resendVerification')->middleware('throttle:5,1')->name('auth.resend-verification');
    Route::post('forgot-password', 'forgotPassword')->middleware('throttle:5,1')->name('auth.forgot-password');
    Route::post('reset-password', 'resetPassword')->middleware('throttle:5,1')->name('auth.reset-password');


    Route::middleware(['auth:api', EnsureTokenVersion::class])->group(function () {
        Route::get('view', 'view')->name('auth.view');
        Route::post('logout', 'logout')->name('auth.logout');
        Route::post('change-password', 'changePassword')->middleware('throttle:5,1')->name('auth.change-password');
    });
});
