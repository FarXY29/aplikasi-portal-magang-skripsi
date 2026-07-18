<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Public\LowonganController as PublicLowonganController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicLowonganController::class, 'index'])->name('home');
Route::get('/lowongan', [PublicLowonganController::class, 'index'])->name('lowongan.index');
Route::get('/lowongan/{id}', [PublicLowonganController::class, 'show'])->name('lowongan.show');

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
    ->middleware('throttle:auth-sensitive')
    ->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
    ->middleware('throttle:auth-sensitive');

Route::get('/scan-qr', [CertificateController::class, 'showScanner'])->name('qr.scanner');
Route::get('/verify-certificate/{token}', [CertificateController::class, 'verify'])->name('certificate.verify');
Route::post('/search-certificate', [CertificateController::class, 'search'])
    ->middleware('throttle:public-search')
    ->name('certificate.search');
