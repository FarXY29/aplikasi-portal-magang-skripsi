<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\AdminKotaController;
use App\Http\Controllers\AdminInstansiController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\PembimbingLapanganController;

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\GoogleAuthController;
use App\Models\InternshipPosition;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PembimbingSekolahController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN PUBLIK (Landing Page dengan Pencarian) ---
Route::get('/', [MagangController::class, 'index'])->name('home');

// Halaman Detail Lowongan (Publik)
Route::get('/lowongan', [MagangController::class, 'index'])->name('lowongan.index');
Route::get('/lowongan/{id}', [MagangController::class, 'show'])->name('lowongan.show');


// --- 2. ROUTE KHUSUS TAMU (GUEST) ---
Route::middleware('guest')->group(function () {
    // Kosong untuk saat ini
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// --- 3. LOGIKA REDIRECT DASHBOARD ---
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    // Redirect user ke dashboard sesuai peran masing-masing
    if ($role == 'admin_kota') return redirect()->route('admin.dashboard');
    if ($role == 'admin_instansi') return redirect()->route('dinas.dashboard');
    if ($role == 'pembimbing_lapangan') return redirect()->route('pembimbing_lapangan.dashboard');
    if ($role == 'peserta') return redirect()->route('peserta.dashboard');
    if ($role == 'pembimbing') return redirect()->route('pembimbing.dashboard');

    
    return view('dashboard'); 
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 4. GROUP ROUTE YANG BUTUH LOGIN (AUTH) ---
Route::middleware('auth')->group(function () {
    
    // A. AREA PESERTA
    Route::middleware(['role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [MagangController::class, 'dashboard'])->name('dashboard');
        Route::get('/daftar/{id}', [MagangController::class, 'showApplyForm'])->name('daftar.form');
        Route::post('/daftar/{id}', [MagangController::class, 'storeApplication'])->name('daftar');
        Route::resource('logbook', LogbookController::class);
        Route::get('/logbook-print', [LogbookController::class, 'print'])->name('logbook.print');
        Route::get('/sertifikat', [MagangController::class, 'downloadCertificate'])->name('sertifikat');
        Route::get('/download-nilai/{id}', [MagangController::class, 'downloadTranskrip'])->name('download.nilai');
        Route::get('/loa/{id}', [MagangController::class, 'downloadLoA'])->name('loa.download');
        Route::get('/id-card/{id}', [MagangController::class, 'downloadIdCard'])->name('id_card.download');
        Route::get('/penempatan-otomatis', [MagangController::class, 'showAutomaticApplyForm'])->name('apply_automatic.form');
        Route::post('/penempatan-otomatis', [MagangController::class, 'storeAutomaticApplication'])->name('apply_automatic.store');
        Route::post('/saran/{id}', [MagangController::class, 'submitSaran'])->name('saran.store');
        Route::post('/lamaran/{id}/batal', [MagangController::class, 'cancelApplication'])->name('lamaran.batal');

        // ROUTE ABSENSI 
        Route::get('/absensi', [App\Http\Controllers\AttendanceController::class, 'index'])->name('absensi.index');
        Route::post('/absen/masuk', [App\Http\Controllers\AttendanceController::class, 'store'])->name('absen.masuk');
        Route::post('/absen/pulang', [App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('absen.pulang');
        Route::post('/absen/izin', [App\Http\Controllers\AttendanceController::class, 'permission'])->name('absen.izin');

    });

    // === TAMBAHKAN ROUTE INI (AJAX CEK KUOTA) ===
    // Ditaruh di luar prefix 'peserta' agar URL-nya tetap /magang/check-availability/...
    Route::middleware(['auth', 'role:peserta'])->group(function () {
        Route::post('/magang/check-availability/{id}', [MagangController::class, 'checkAvailability'])
            ->name('magang.check.availability');
    });

    // B. AREA ADMIN INSTANSI
    Route::middleware(['role:admin_instansi'])->prefix('dinas')->name('dinas.')->group(function () {
        
        Route::get('/dashboard', [AdminInstansiController::class, 'index'])->name('dashboard');
        
        // Manajemen Pembimbing Lapangan
        Route::get('/pembimbing_lapangan', [AdminInstansiController::class, 'indexPembimbingLapangan'])->name('pembimbing_lapangan.index');
        Route::post('/pembimbing_lapangan', [AdminInstansiController::class, 'storePembimbingLapangan'])->name('pembimbing_lapangan.store');
        Route::get('/pembimbing_lapangan/{id}/edit', [AdminInstansiController::class, 'editPembimbingLapangan'])->name('pembimbing_lapangan.edit');
        Route::put('/pembimbing_lapangan/{id}', [AdminInstansiController::class, 'updatePembimbingLapangan'])->name('pembimbing_lapangan.update');
        Route::delete('/pembimbing_lapangan/{id}', [AdminInstansiController::class, 'destroyPembimbingLapangan'])->name('pembimbing_lapangan.destroy');

        // Manajemen Pelamar & Lowongan
        Route::get('/pelamar', [AdminInstansiController::class, 'applicants'])->name('pelamar');
        Route::get('/pelamar/{id}/download-surat', [AdminInstansiController::class, 'downloadSurat'])->name('pelamar.download_surat');
        Route::post('/pelamar/{id}/terima', [AdminInstansiController::class, 'acceptApplicant'])->name('pelamar.terima');
        Route::post('/pelamar/{id}/tolak', [AdminInstansiController::class, 'rejectApplicant'])->name('pelamar.tolak');
        Route::get('/lowongan', [AdminInstansiController::class, 'indexLowongan'])->name('lowongan.index');
        Route::get('/lowongan/create', [AdminInstansiController::class, 'createLowongan'])->name('lowongan.create');
        Route::post('/lowongan', [AdminInstansiController::class, 'storeLowongan'])->name('lowongan.store');
        Route::get('/lowongan/{id}/edit', [AdminInstansiController::class, 'editLowongan'])->name('lowongan.edit');
        Route::put('/lowongan/{id}', [AdminInstansiController::class, 'updateLowongan'])->name('lowongan.update');
        Route::delete('/lowongan/{id}', [AdminInstansiController::class, 'destroyLowongan'])->name('lowongan.destroy');

        // Monitoring & Validasi
        Route::get('/peserta-aktif', [AdminInstansiController::class, 'activeInterns'])->name('peserta.index');
        Route::post('/peserta/{id}/assign', [AdminInstansiController::class, 'assignPembimbingLapangan'])->name('peserta.assign');
        Route::get('/peserta/{id}/logbook', [AdminInstansiController::class, 'showLogbooks'])->name('peserta.logbook');
        Route::get('/peserta/{id}/absensi', [AdminInstansiController::class, 'showAbsensi'])->name('peserta.absensi');
        Route::post('/peserta/{id}/selesai', [AdminInstansiController::class, 'finishIntern'])->name('peserta.selesai');
        Route::post('/peserta/{id}/keluarkan', [AdminInstansiController::class, 'expelIntern'])->name('peserta.keluarkan');
        Route::post('/logbook/validasi/{id}', [AdminInstansiController::class, 'validateLogbook'])->name('logbook.validasi');
        
        // Pusat Laporan Hub
        Route::get('/pusat-laporan', [AdminInstansiController::class, 'laporanHub'])->name('laporan.hub');

        // Laporan & Cetak
        Route::get('/laporan/rekap', [AdminInstansiController::class, 'laporanRekap'])->name('laporan.rekap');
        Route::get('/laporan/rekap/print', [AdminInstansiController::class, 'printRekap'])->name('laporan.rekap.print');
        Route::get('/laporan/kinerja-mahasiswa', [AdminInstansiController::class, 'laporanKinerjaMahasiswa'])->name('laporan.kinerja_mahasiswa');
        Route::get('/laporan/kinerja-mahasiswa/print', [AdminInstansiController::class, 'printKinerjaMahasiswa'])->name('laporan.kinerja_mahasiswa.print');
        Route::get('/laporan/beban-pembimbing', [AdminInstansiController::class, 'laporanBebanPembimbing'])->name('laporan.beban_pembimbing');
        Route::get('/laporan/beban-pembimbing/print', [AdminInstansiController::class, 'printBebanPembimbing'])->name('laporan.beban_pembimbing.print');
        // Laporan Demografi Kampus
        Route::get('/laporan/demografi-kampus', [AdminInstansiController::class, 'laporanDemografiKampus'])->name('laporan.demografi_kampus');
        Route::get('/laporan/demografi-kampus/print', [AdminInstansiController::class, 'printDemografiKampus'])->name('laporan.demografi_kampus.print');
        // Laporan Jurnal Harian
        Route::get('/laporan/jurnal-harian', [AdminInstansiController::class, 'laporanJurnalHarian'])->name('laporan.jurnal_harian');
        Route::get('/laporan/jurnal-harian/print', [AdminInstansiController::class, 'printJurnalHarian'])->name('laporan.jurnal_harian.print');
        
        Route::get('/peserta/{id}/absensi/pdf', [AdminInstansiController::class, 'printAbsensi'])->name('peserta.absensi.pdf');

        // Pengaturan
        Route::get('/pengaturan-pejabat', [AdminInstansiController::class, 'editPejabat'])->name('pejabat.edit');
        Route::put('/pengaturan-pejabat', [AdminInstansiController::class, 'updatePejabat'])->name('pejabat.update');
        Route::get('/pengaturan', [AdminInstansiController::class, 'settings'])->name('settings');
        Route::put('/pengaturan', [AdminInstansiController::class, 'updateSettings'])->name('settings.update');

        // === MANAJEMEN SERTIFIKAT ===
        Route::get('/sertifikat/create/{applicationId}', [CertificateController::class, 'create'])->name('sertifikat.create');
        Route::post('/sertifikat/store/{applicationId}', [CertificateController::class, 'store'])->name('sertifikat.store');

    });

    // C. AREA PEMBIMBING LAPANGAN
    Route::middleware(['role:pembimbing_lapangan'])->prefix('pembimbing_lapangan')->name('pembimbing_lapangan.')->group(function () {
        Route::get('/dashboard', [PembimbingLapanganController::class, 'index'])->name('dashboard');
        Route::get('/mahasiswa/{id}/logbook', [PembimbingLapanganController::class, 'showLogbook'])->name('logbook');
        Route::post('/logbook/validasi/{id}', [PembimbingLapanganController::class, 'validateLogbook'])->name('logbook.validasi');
        Route::get('/mahasiswa/{id}/nilai', [PembimbingLapanganController::class, 'gradingForm'])->name('grading.form');
        Route::post('/mahasiswa/{id}/nilai', [PembimbingLapanganController::class, 'storeGrade'])->name('grading.store');
        // 1. Rute MENAMPILKAN Form (Method GET)
        Route::get('/penilaian/{id}', [PembimbingLapanganController::class, 'formPenilaian'])->name('penilaian');

        // 2. Rute MENYIMPAN Data (Method POST) 
        Route::post('/penilaian/{id}', [PembimbingLapanganController::class, 'simpanNilai'])->name('simpan_nilai');  
        // ROUTE ABSENSI PEMBIMBING LAPANGAN
        Route::get('/absensi', [PembimbingLapanganController::class, 'attendance'])->name('attendance.index');
        Route::post('/absensi/{id}/validasi', [PembimbingLapanganController::class, 'validateAttendance'])->name('attendance.validate');
    });

    // D. AREA PEMBIMBING AKADEMIK
    Route::middleware(['role:pembimbing'])->prefix('pembimbing')->name('pembimbing.')->group(function () {
        Route::get('/dashboard', [PembimbingSekolahController::class, 'index'])->name('dashboard');
        Route::get('/peserta/{id}/logbook', [PembimbingSekolahController::class, 'logbook'])->name('peserta.logbook');
        Route::get('/peserta/{id}/absensi', [PembimbingSekolahController::class, 'absensi'])->name('peserta.absensi');
    });


    // E. AREA ADMIN KOTA (SUPER ADMIN)
    Route::middleware(['role:admin_kota'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminKotaController::class, 'index'])->name('dashboard');
        
        // Master Data
        Route::get('/instansi', [AdminKotaController::class, 'indexInstansi'])->name('instansi.index');
        Route::get('/instansi/create', [AdminKotaController::class, 'create'])->name('instansi.create');
        Route::post('/instansi', [AdminKotaController::class, 'store'])->name('instansi.store');
        Route::get('/instansi/{id}/edit', [AdminKotaController::class, 'edit'])->name('instansi.edit');
        Route::put('/instansi/{id}', [AdminKotaController::class, 'update'])->name('instansi.update');
        Route::delete('/instansi/{id}', [AdminKotaController::class, 'destroy'])->name('instansi.destroy');
        
        // Laporan Global & Monitoring
        Route::get('/laporan', [AdminKotaController::class, 'report'])->name('laporan');
        Route::get('/laporan/print', [AdminKotaController::class, 'printLaporan'])->name('laporan.print');
        
        // Pusat Laporan Hub
        Route::get('/pusat-laporan', [AdminKotaController::class, 'laporanHub'])->name('laporan.hub');

        // Laporan Global
        Route::get('/laporan-peserta-global', [AdminKotaController::class, 'laporanPesertaGlobal'])->name('laporan.peserta_global');
        Route::get('/laporan-instansi', [AdminKotaController::class, 'laporanInstansi'])->name('laporan.instansi');
        // Laporan INSTANSI PDF
        Route::get('/instansi/cetak-pdf', [AdminKotaController::class, 'printInstansi'])->name('instansi.print_pdf');
        Route::get('/laporan/peserta-global/print', [AdminKotaController::class, 'printPesertaGlobal'])
        ->name('laporan.peserta_global.print');
        // Laporan Grading
        Route::get('/laporan-grading', [AdminKotaController::class, 'laporanGrading'])->name('laporan.grading');
        Route::get('/laporan-grading/print', [AdminKotaController::class, 'printGrading'])->name('laporan.grading.print');
        Route::get('/laporan-instansi-disiplin', [AdminKotaController::class, 'laporanInstansiDisiplin'])->name('laporan.instansi_disiplin');
        Route::get('/laporan-instansi-disiplin/print', [AdminKotaController::class, 'printInstansiDisiplin'])->name('laporan.instansi_disiplin.print');
        Route::get('/laporan-durasi-magang', [AdminKotaController::class, 'laporanDurasiMagang'])->name('laporan.durasi_magang');
        Route::get('/laporan-durasi-magang/print', [AdminKotaController::class, 'printDurasiMagang'])->name('laporan.durasi_magang.print');
        Route::get('/laporan-demografi-jurusan', [AdminKotaController::class, 'laporanDemografiJurusan'])->name('laporan.demografi_jurusan');
        Route::get('/laporan-demografi-jurusan/print', [AdminKotaController::class, 'printDemografiJurusan'])->name('laporan.demografi_jurusan.print');
        Route::get('/laporan-penyerapan-kuota', [AdminKotaController::class, 'laporanPenyerapanKuota'])->name('laporan.penyerapan_kuota');
        Route::get('/laporan-penyerapan-kuota/print', [AdminKotaController::class, 'printPenyerapanKuota'])->name('laporan.penyerapan_kuota.print');
        // User Management & Settings
        Route::resource('users', AdminUserController::class);
        Route::get('/monitoring-logbook', [AdminUserController::class, 'logbooks'])->name('users.logbooks');
        Route::get('/monitoring-logbook/{id}', [AdminUserController::class, 'showLogbook'])->name('users.logbooks.show');
        
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/backup', [AdminSettingController::class, 'backupDatabase'])->name('settings.backup');
    });

    // G. PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Route Publik untuk Verifikasi
    Route::get('/scan-qr', [CertificateController::class, 'showScanner'])->name('qr.scanner');
    Route::get('/verify-certificate/{token}', [CertificateController::class, 'verify'])->name('certificate.verify');
    Route::post('/search-certificate', [CertificateController::class, 'search'])->name('certificate.search');

require __DIR__.'/auth.php';