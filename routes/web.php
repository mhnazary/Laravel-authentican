<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

use App\Http\Controllers\Auth\EmailVerificationController;

Route::get('/', function () {
    return redirect()->route('');
});

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Authenticated-only routes
Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('/email/verify', [EmailVerificationController::class, 'showPrompt'])->name('verification.notice');
    
    Route::post('/email/verify', [EmailVerificationController::class, 'verify'])
        ->middleware('throttle:3,1')   // 3 attempts/min; controller invalidates code after 3 wrong guesses
        ->name('verification.verify');
        
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:1,1')   // 1 resend/min — prevents inbox flooding
        ->name('verification.send');

    // Dashboard is protected by both 'auth' and 'verified' middlewares
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
