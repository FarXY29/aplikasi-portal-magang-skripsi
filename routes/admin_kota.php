<?php

use App\Http\Controllers\AdminKota\DashboardController as AdminKotaDashboardController;
use App\Http\Controllers\AdminKota\InstansiController as AdminKotaInstansiController;
use App\Http\Controllers\AdminKota\ReportController as AdminKotaReportController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminKota\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin_kota'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminKotaDashboardController::class, 'index'])->name('dashboard');

    Route::get('/audit-trail', [AuditLogController::class, 'index'])->name('audit_trail');

    Route::get('/instansi', [AdminKotaInstansiController::class, 'indexInstansi'])->name('instansi.index');
    Route::get('/instansi/create', [AdminKotaInstansiController::class, 'create'])->name('instansi.create');
    Route::post('/instansi', [AdminKotaInstansiController::class, 'store'])->name('instansi.store');
    Route::get('/instansi/{id}/edit', [AdminKotaInstansiController::class, 'edit'])->name('instansi.edit');
    Route::put('/instansi/{id}', [AdminKotaInstansiController::class, 'update'])->name('instansi.update');
    Route::delete('/instansi/{id}', [AdminKotaInstansiController::class, 'destroy'])->name('instansi.destroy');
    Route::get('/instansi/cetak-pdf', [AdminKotaReportController::class, 'printInstansi'])->name('instansi.print_pdf');

    Route::get('/laporan', [AdminKotaReportController::class, 'report'])->name('laporan');
    Route::get('/laporan/print', [AdminKotaReportController::class, 'printLaporan'])->name('laporan.print');
    Route::get('/pusat-laporan', [AdminKotaReportController::class, 'laporanHub'])->name('laporan.hub');
    Route::get('/laporan-peserta-global', [AdminKotaReportController::class, 'laporanPesertaGlobal'])->name('laporan.peserta_global');
    Route::get('/laporan-instansi', [AdminKotaReportController::class, 'laporanInstansi'])->name('laporan.instansi');
    Route::get('/laporan/peserta-global/print', [AdminKotaReportController::class, 'printPesertaGlobal'])->name('laporan.peserta_global.print');
    Route::get('/laporan-grading', [AdminKotaReportController::class, 'laporanGrading'])->name('laporan.grading');
    Route::get('/laporan-grading/print', [AdminKotaReportController::class, 'printGrading'])->name('laporan.grading.print');
    Route::get('/laporan-instansi-disiplin', [AdminKotaReportController::class, 'laporanInstansiDisiplin'])->name('laporan.instansi_disiplin');
    Route::get('/laporan-instansi-disiplin/print', [AdminKotaReportController::class, 'printInstansiDisiplin'])->name('laporan.instansi_disiplin.print');
    Route::get('/laporan-durasi-magang', [AdminKotaReportController::class, 'laporanDurasiMagang'])->name('laporan.durasi_magang');
    Route::get('/laporan-durasi-magang/print', [AdminKotaReportController::class, 'printDurasiMagang'])->name('laporan.durasi_magang.print');
    Route::get('/laporan-demografi-jurusan', [AdminKotaReportController::class, 'laporanDemografiJurusan'])->name('laporan.demografi_jurusan');
    Route::get('/laporan-demografi-jurusan/print', [AdminKotaReportController::class, 'printDemografiJurusan'])->name('laporan.demografi_jurusan.print');
    Route::get('/laporan-penyerapan-kuota', [AdminKotaReportController::class, 'laporanPenyerapanKuota'])->name('laporan.penyerapan_kuota');
    Route::get('/laporan-penyerapan-kuota/print', [AdminKotaReportController::class, 'printPenyerapanKuota'])->name('laporan.penyerapan_kuota.print');

    Route::resource('users', AdminUserController::class);
    Route::get('/monitoring-logbook', [AdminUserController::class, 'logbooks'])->name('users.logbooks');
    Route::get('/monitoring-logbook/{id}', [AdminUserController::class, 'showLogbook'])->name('users.logbooks.show');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/backup', [AdminSettingController::class, 'requestBackup'])->name('settings.backup');
    Route::get('/settings/backups/{backup}/download', [AdminSettingController::class, 'downloadBackup'])
        ->middleware('signed')
        ->name('settings.backups.download');
});
