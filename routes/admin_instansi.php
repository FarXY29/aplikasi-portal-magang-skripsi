<?php

use App\Http\Controllers\AdminInstansi\ActiveInternController as AdminInstansiActiveInternController;
use App\Http\Controllers\AdminInstansi\ApplicantController as AdminInstansiApplicantController;
use App\Http\Controllers\AdminInstansi\DashboardController as AdminInstansiDashboardController;
use App\Http\Controllers\AdminInstansi\LowonganController as AdminInstansiLowonganController;
use App\Http\Controllers\AdminInstansi\PembimbingLapanganController as AdminInstansiPLController;
use App\Http\Controllers\AdminInstansi\ReportController as AdminInstansiReportController;
use App\Http\Controllers\AdminInstansi\SettingController as AdminInstansiSettingController;
use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin_instansi'])->prefix('dinas')->name('dinas.')->group(function () {
    Route::get('/dashboard', [AdminInstansiDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pembimbing_lapangan', [AdminInstansiPLController::class, 'indexPembimbingLapangan'])->name('pembimbing_lapangan.index');
    Route::post('/pembimbing_lapangan', [AdminInstansiPLController::class, 'storePembimbingLapangan'])->name('pembimbing_lapangan.store');
    Route::get('/pembimbing_lapangan/{id}/edit', [AdminInstansiPLController::class, 'editPembimbingLapangan'])->name('pembimbing_lapangan.edit');
    Route::put('/pembimbing_lapangan/{id}', [AdminInstansiPLController::class, 'updatePembimbingLapangan'])->name('pembimbing_lapangan.update');
    Route::delete('/pembimbing_lapangan/{id}', [AdminInstansiPLController::class, 'destroyPembimbingLapangan'])->name('pembimbing_lapangan.destroy');

    Route::get('/pelamar', [AdminInstansiApplicantController::class, 'applicants'])->name('pelamar');
    Route::get('/pelamar/{id}/download-surat', [AdminInstansiApplicantController::class, 'downloadSurat'])->name('pelamar.download_surat');
    Route::post('/pelamar/{id}/terima', [AdminInstansiApplicantController::class, 'acceptApplicant'])->name('pelamar.terima');
    Route::post('/pelamar/{id}/tolak', [AdminInstansiApplicantController::class, 'rejectApplicant'])->name('pelamar.tolak');

    Route::get('/lowongan', [AdminInstansiLowonganController::class, 'indexLowongan'])->name('lowongan.index');
    Route::get('/lowongan/create', [AdminInstansiLowonganController::class, 'createLowongan'])->name('lowongan.create');
    Route::post('/lowongan', [AdminInstansiLowonganController::class, 'storeLowongan'])->name('lowongan.store');
    Route::get('/lowongan/{id}/edit', [AdminInstansiLowonganController::class, 'editLowongan'])->name('lowongan.edit');
    Route::put('/lowongan/{id}', [AdminInstansiLowonganController::class, 'updateLowongan'])->name('lowongan.update');
    Route::delete('/lowongan/{id}', [AdminInstansiLowonganController::class, 'destroyLowongan'])->name('lowongan.destroy');

    Route::get('/peserta-aktif', [AdminInstansiActiveInternController::class, 'activeInterns'])->name('peserta.index');
    Route::post('/peserta/{id}/assign', [AdminInstansiActiveInternController::class, 'assignPembimbingLapangan'])->name('peserta.assign');
    Route::get('/peserta/{id}/logbook', [AdminInstansiActiveInternController::class, 'showLogbooks'])->name('peserta.logbook');
    Route::get('/peserta/{id}/absensi', [AdminInstansiActiveInternController::class, 'showAbsensi'])->name('peserta.absensi');
    Route::post('/peserta/{id}/selesai', [AdminInstansiActiveInternController::class, 'finishIntern'])->name('peserta.selesai');
    Route::post('/peserta/{id}/keluarkan', [AdminInstansiActiveInternController::class, 'expelIntern'])->name('peserta.keluarkan');
    Route::post('/logbook/validasi/{id}', [AdminInstansiActiveInternController::class, 'validateLogbook'])->name('logbook.validasi');
    Route::get('/peserta/{id}/absensi/pdf', [AdminInstansiActiveInternController::class, 'printAbsensi'])->name('peserta.absensi.pdf');

    Route::get('/pusat-laporan', [AdminInstansiReportController::class, 'laporanHub'])->name('laporan.hub');
    Route::get('/laporan/rekap', [AdminInstansiReportController::class, 'laporanRekap'])->name('laporan.rekap');
    Route::get('/laporan/rekap/print', [AdminInstansiReportController::class, 'printRekap'])->name('laporan.rekap.print');
    Route::get('/laporan/kinerja-mahasiswa', [AdminInstansiReportController::class, 'laporanKinerjaMahasiswa'])->name('laporan.kinerja_mahasiswa');
    Route::get('/laporan/kinerja-mahasiswa/print', [AdminInstansiReportController::class, 'printKinerjaMahasiswa'])->name('laporan.kinerja_mahasiswa.print');
    Route::get('/laporan/beban-pembimbing', [AdminInstansiReportController::class, 'laporanBebanPembimbing'])->name('laporan.beban_pembimbing');
    Route::get('/laporan/beban-pembimbing/print', [AdminInstansiReportController::class, 'printBebanPembimbing'])->name('laporan.beban_pembimbing.print');
    Route::get('/laporan/demografi-kampus', [AdminInstansiReportController::class, 'laporanDemografiKampus'])->name('laporan.demografi_kampus');
    Route::get('/laporan/demografi-kampus/print', [AdminInstansiReportController::class, 'printDemografiKampus'])->name('laporan.demografi_kampus.print');
    Route::get('/laporan/jurnal-harian', [AdminInstansiReportController::class, 'laporanJurnalHarian'])->name('laporan.jurnal_harian');
    Route::get('/laporan/jurnal-harian/print', [AdminInstansiReportController::class, 'printJurnalHarian'])->name('laporan.jurnal_harian.print');

    Route::get('/pengaturan-pejabat', [AdminInstansiSettingController::class, 'editPejabat'])->name('pejabat.edit');
    Route::put('/pengaturan-pejabat', [AdminInstansiSettingController::class, 'updatePejabat'])->name('pejabat.update');
    Route::get('/pengaturan', [AdminInstansiSettingController::class, 'settings'])->name('settings');
    Route::put('/pengaturan', [AdminInstansiSettingController::class, 'updateSettings'])->name('settings.update');

    Route::get('/sertifikat/create/{applicationId}', [CertificateController::class, 'create'])->name('sertifikat.create');
    Route::post('/sertifikat/store/{applicationId}', [CertificateController::class, 'store'])->name('sertifikat.store');
});
