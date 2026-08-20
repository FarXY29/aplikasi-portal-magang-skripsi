<?php

use App\Http\Controllers\AdminKota\AuditLogController;
use App\Http\Controllers\AdminKota\CertificateGovernanceController;
use App\Http\Controllers\AdminKota\DashboardController as AdminKotaDashboardController;
use App\Http\Controllers\AdminKota\InstansiController as AdminKotaInstansiController;
use App\Http\Controllers\AdminKota\MajorCategoryController;
use App\Http\Controllers\AdminKota\MajorController;
use App\Http\Controllers\AdminKota\ReportController as AdminKotaReportController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin_kota'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminKotaDashboardController::class, 'index'])->name('dashboard');

    Route::get('/audit-trail', [AuditLogController::class, 'index'])->name('audit_trail');

    // Data Master Instansi / OPD
    Route::get('/instansi', [AdminKotaInstansiController::class, 'indexInstansi'])->name('instansi.index');
    Route::get('/instansi/create', [AdminKotaInstansiController::class, 'create'])->name('instansi.create');
    Route::post('/instansi', [AdminKotaInstansiController::class, 'store'])->name('instansi.store');
    Route::get('/instansi/{id}/edit', [AdminKotaInstansiController::class, 'edit'])->name('instansi.edit');
    Route::put('/instansi/{id}', [AdminKotaInstansiController::class, 'update'])->name('instansi.update');
    Route::delete('/instansi/{id}', [AdminKotaInstansiController::class, 'destroy'])->name('instansi.destroy');
    Route::get('/instansi/cetak-pdf', [AdminKotaReportController::class, 'printInstansi'])->name('instansi.print_pdf');

    // Data Master Rumpun Keilmuan & Program Studi (Prioritas Tinggi)
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('major-categories', MajorCategoryController::class)->except(['show']);
        Route::resource('majors', MajorController::class)->except(['show']);
        Route::post('majors/{id}/toggle-status', [MajorController::class, 'toggleStatus'])->name('majors.toggle');
    });

    // Registri & Tata Kelola Sertifikat Resmi Kota (Prioritas Tinggi)
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateGovernanceController::class, 'index'])->name('index');
        Route::get('/export-pdf', [CertificateGovernanceController::class, 'exportPdf'])->name('export_pdf');
        Route::get('/{id}', [CertificateGovernanceController::class, 'show'])->name('show');
        Route::post('/{id}/revoke', [CertificateGovernanceController::class, 'revoke'])->name('revoke');
        Route::post('/{id}/restore', [CertificateGovernanceController::class, 'restore'])->name('restore');
    });

    // Pusat Laporan
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

    // Manajemen Pengguna & Monitoring Logbook
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('/monitoring-logbook', [AdminUserController::class, 'logbooks'])->name('users.logbooks');
    Route::get('/monitoring-logbook/{id}', [AdminUserController::class, 'showLogbook'])->name('users.logbooks.show');

    // Pengaturan Sistem & Backup
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/backup', [AdminSettingController::class, 'requestBackup'])->name('settings.backup');
    Route::get('/settings/backups/{backup}/download', [AdminSettingController::class, 'downloadBackup'])
        ->middleware('signed')
        ->name('settings.backups.download');
    Route::delete('/settings/backups/{backup}', [AdminSettingController::class, 'destroyBackup'])->name('settings.backups.destroy');
});
