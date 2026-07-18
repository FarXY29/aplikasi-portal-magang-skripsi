<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\Peserta\ApplicationController as PesertaApplicationController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/daftar/{id}', [PesertaApplicationController::class, 'showApplyForm'])->name('daftar.form');
    Route::post('/daftar/{id}', [PesertaApplicationController::class, 'storeApplication'])->name('daftar');
    Route::resource('logbook', LogbookController::class);
    Route::get('/logbook-print', [LogbookController::class, 'print'])->name('logbook.print');
    Route::get('/sertifikat', [PesertaDashboardController::class, 'downloadCertificate'])->name('sertifikat');
    Route::get('/download-nilai/{id}', [PesertaDashboardController::class, 'downloadTranskrip'])->name('download.nilai');
    Route::get('/loa/{id}', [PesertaDashboardController::class, 'downloadLoA'])->name('loa.download');
    Route::get('/id-card/{id}', [PesertaDashboardController::class, 'downloadIdCard'])->name('id_card.download');
    Route::get('/penempatan-otomatis', [PesertaApplicationController::class, 'showAutomaticApplyForm'])->name('apply_automatic.form');
    Route::post('/penempatan-otomatis', [PesertaApplicationController::class, 'storeAutomaticApplication'])->name('apply_automatic.store');
    Route::post('/saran/{id}', [PesertaApplicationController::class, 'submitSaran'])->name('saran.store');
    Route::post('/lamaran/{id}/batal', [PesertaDashboardController::class, 'cancelApplication'])->name('lamaran.batal');

    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absen/masuk', [AttendanceController::class, 'store'])->name('absen.masuk');
    Route::post('/absen/pulang', [AttendanceController::class, 'clockOut'])->name('absen.pulang');
    Route::post('/absen/izin', [AttendanceController::class, 'permission'])->name('absen.izin');
});

Route::middleware(['auth', 'role:peserta', 'throttle:availability-check'])->group(function () {
    Route::post('/magang/check-availability/{id}', [PesertaApplicationController::class, 'checkAvailability'])
        ->name('magang.check.availability');
});
