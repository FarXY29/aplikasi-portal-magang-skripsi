<?php

use App\Http\Controllers\PembimbingLapanganController;
use App\Http\Controllers\PembimbingSekolahController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:pembimbing_lapangan'])->prefix('pembimbing_lapangan')->name('pembimbing_lapangan.')->group(function () {
    Route::get('/dashboard', [PembimbingLapanganController::class, 'index'])->name('dashboard');
    Route::get('/mahasiswa/{id}/logbook', [PembimbingLapanganController::class, 'showLogbook'])->name('logbook');
    Route::post('/logbook/batch-validasi', [PembimbingLapanganController::class, 'batchValidateLogbook'])->name('logbook.batch_validasi');
    Route::post('/logbook/validasi/{id}', [PembimbingLapanganController::class, 'validateLogbook'])->name('logbook.validasi');
    Route::get('/mahasiswa/{id}/nilai', [PembimbingLapanganController::class, 'gradingForm'])->name('grading.form');
    Route::post('/mahasiswa/{id}/nilai', [PembimbingLapanganController::class, 'storeGrade'])->name('grading.store');
    Route::get('/penilaian/{id}', [PembimbingLapanganController::class, 'formPenilaian'])->name('penilaian');
    Route::post('/penilaian/{id}', [PembimbingLapanganController::class, 'simpanNilai'])->name('simpan_nilai');
    Route::get('/absensi', [PembimbingLapanganController::class, 'attendance'])->name('attendance.index');
    Route::post('/absensi/{id}/validasi', [PembimbingLapanganController::class, 'validateAttendance'])->name('attendance.validate');
});

Route::middleware(['auth', 'role:pembimbing'])->prefix('pembimbing')->name('pembimbing.')->group(function () {
    Route::get('/dashboard', [PembimbingSekolahController::class, 'index'])->name('dashboard');
    Route::get('/peserta/{id}/logbook', [PembimbingSekolahController::class, 'logbook'])->name('peserta.logbook');
    Route::get('/peserta/{id}/absensi', [PembimbingSekolahController::class, 'absensi'])->name('peserta.absensi');
});
