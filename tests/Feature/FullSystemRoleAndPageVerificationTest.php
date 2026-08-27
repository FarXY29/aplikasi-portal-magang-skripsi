<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\DailyLog;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\Major;
use App\Models\MajorCategory;
use App\Models\University;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FullSystemRoleAndPageVerificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        Storage::fake('local');
        Storage::fake('public');
    }

    // ==========================================
    // 1. PUBLIC & GUEST ACCESS TESTS
    // ==========================================

    public function test_guest_can_access_all_public_pages(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kominfo Banjarmasin',
            'kode_unit_kerja' => 'DISKOMINFO-01',
            'alamat' => 'Jl. RE Martadinata',
        ]);

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Frontend Developer',
            'kuota' => 5,
            'status' => 'buka',
            'deskripsi' => 'Pengembangan UI/UX',
            'persyaratan' => 'HTML, CSS, JS',
        ]);

        // Home
        $res = $this->get(route('home'));
        $res->assertOk();

        // Lowongan list
        $res = $this->get(route('lowongan.index'));
        $res->assertOk();

        // Lowongan detail
        $res = $this->get(route('lowongan.show', $posisi->id));
        $res->assertOk();
        $res->assertSee('Frontend Developer');

        // Auth pages
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
        $this->get(route('password.request'))->assertOk();
        $this->get(route('password.reset', ['token' => 'dummy-token']))->assertOk();

        // QR Scanner & Verification
        $this->get(route('qr.scanner'))->assertOk();
        $this->get(route('certificate.verify', 'DUMMY-TOKEN'))->assertOk();
        $this->get(route('id_card.verify', 'DUMMY-ID-CARD-TOKEN'))->assertOk();

        // Certificate Search
        $resSearch = $this->post(route('certificate.search'), ['nomor_sertifikat' => 'NON-EXISTENT']);
        $resSearch->assertSessionHas('error');
    }

    // ==========================================
    // 2. PESERTA ROLE WORKFLOW & PAGES
    // ==========================================

    public function test_peserta_end_to_end_journey_and_pages(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Banjarmasin',
            'nama_pejabat' => 'Drs. H. Pejabat, M.Pd',
            'nip_pejabat' => '197001011995031001',
            'jabatan_pejabat' => 'Kepala Dinas Pendidikan',
            'kode_unit_kerja' => 'DISDIK-01',
            'alamat' => 'Jl. Tendean',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 500,
            'jam_mulai_masuk' => '00:00:00',
            'jam_mulai_pulang' => '00:00:00',
        ]);

        $category = MajorCategory::create([
            'code' => 'TIK',
            'name' => 'Teknologi Informasi & Komputer',
        ]);

        $major = Major::create([
            'major_category_id' => $category->id,
            'name' => 'Teknik Informatika',
            'degree_level' => 'S1',
            'is_active' => true,
        ]);

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff IT Disdik',
            'required_major_category_id' => $category->id,
            'required_major' => 'Teknik Informatika',
            'kuota' => 2,
            'status' => 'buka',
            'deskripsi' => 'Pengelolaan data pendidikan',
            'persyaratan' => 'Mahasiswa S1',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'major_id' => $major->id,
            'major' => 'Teknik Informatika',
            'nik' => '6371010000000001',
            'asal_instansi' => 'Universitas Lambung Mangkurat',
            'email_verified_at' => now(),
        ]);
        $peserta->assignRole('peserta');

        // Dashboard redirect
        $this->actingAs($peserta)->get('/dashboard')->assertRedirect(route('peserta.dashboard'));

        // Peserta Dashboard (No application yet)
        $this->actingAs($peserta)->get(route('peserta.dashboard'))->assertOk();

        // Profile Page
        $this->actingAs($peserta)->get(route('profile.edit'))->assertOk();
        $this->actingAs($peserta)->patch(route('profile.update'), [
            'name' => 'Peserta ULM Terupdate',
            'email' => $peserta->email,
            'phone' => '081234567890',
        ])->assertRedirect(route('profile.edit'));

        // Automatic Placement Form
        $this->actingAs($peserta)->get(route('peserta.apply_automatic.form'))->assertOk();

        // Apply Form
        $this->actingAs($peserta)->get(route('peserta.daftar.form', $posisi->id))->assertOk();

        // Submit Application
        $suratFile = UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf');

        $resApply = $this->actingAs($peserta)->post(route('peserta.daftar', $posisi->id), [
            'tanggal_mulai' => now()->addDays(2)->toDateString(),
            'tanggal_selesai' => now()->addDays(92)->toDateString(),
            'surat' => $suratFile,
        ]);
        $resApply->assertRedirect(route('peserta.dashboard'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'status' => 'pending',
        ]);

        $application = Application::where('user_id', $peserta->id)->first();

        // Cancel application test
        $this->actingAs($peserta)->post(route('peserta.lamaran.batal', $application->id))
            ->assertSessionHas('success');
        $this->assertEquals('dibatalkan', $application->fresh()->status_value);

        // Re-apply for active test
        $application->update([
            'status' => 'diterima',
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(85)->toDateString(),
        ]);

        // Dashboard with accepted application
        $this->actingAs($peserta)->get(route('peserta.dashboard'))->assertOk();

        // Download LoA & ID Card
        $this->actingAs($peserta)->get(route('peserta.loa.download', $application->id))->assertOk();
        $this->actingAs($peserta)->get(route('peserta.id_card.download', $application->id))->assertOk();

        // Verify ID Card Public Route
        $resIdCardVerify = $this->get(route('id_card.verify', $application->fresh()->token_verifikasi));
        $resIdCardVerify->assertOk();
        $resIdCardVerify->assertSee($peserta->name);
        $resIdCardVerify->assertSee('Peserta Magang Aktif');

        // Attendance Page
        $this->actingAs($peserta)->get(route('peserta.absensi.index'))->assertOk();

        // Clock In
        $resAbsen = $this->actingAs($peserta)->post(route('peserta.absen.masuk'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'keterangan' => 'Hadir tepat waktu',
        ]);
        $resAbsen->assertSessionHas('success');

        // Clock Out
        $resPulang = $this->actingAs($peserta)->post(route('peserta.absen.pulang'), [
            'latitude' => -3.316694,
            'longitude' => 114.590111,
        ]);
        $resPulang->assertSessionHas('success');

        // Submit Permission (Izin) on a fresh date / record
        Attendance::where('application_id', $application->id)->delete();
        $izinFile = UploadedFile::fake()->image('surat_dokter.jpg');
        $resIzin = $this->actingAs($peserta)->post(route('peserta.absen.izin'), [
            'status' => 'izin',
            'description' => 'Ada keperluan akademik kampus',
            'proof_file' => $izinFile,
        ]);
        $resIzin->assertSessionHas('success');

        // Logbook Page & CRUD
        $this->actingAs($peserta)->get(route('peserta.logbook.index'))->assertOk();

        $logbookPhoto = UploadedFile::fake()->image('kegiatan.jpg');
        $resLogStore = $this->actingAs($peserta)->post(route('peserta.logbook.store'), [
            'kegiatan' => 'Merancang database sistem aplikasi',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'foto' => $logbookPhoto,
        ]);
        $resLogStore->assertSessionHas('success');

        $log = DailyLog::where('application_id', $application->id)->first();
        $this->assertNotNull($log);

        // Update Logbook
        $this->actingAs($peserta)->put(route('peserta.logbook.update', $log->id), [
            'kegiatan' => 'Merancang arsitektur database MySQL dan Redis',
        ])->assertSessionHas('success');

        // Print Logbook PDF
        $this->actingAs($peserta)->get(route('peserta.logbook.print', $application->id))->assertOk();

        // Finish Internship Transition & Certificate
        $application->update([
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'nilai_integritas' => 90,
            'nilai_keahlian' => 88,
            'nilai_disiplin' => 95,
            'nilai_kerjasama' => 90,
            'nilai_inisiatif' => 87,
            'nilai_kehadiran' => 100,
            'nilai_rata_rata' => 91.67,
            'nomor_sertifikat' => '001/DISDIK/2026',
            'token_verifikasi' => 'TOKENTEST999999',
            'sertifikat_diterbitkan' => true,
        ]);

        Certificate::create([
            'application_id' => $application->id,
            'nomor_sertifikat' => $application->nomor_sertifikat,
            'token_verifikasi' => $application->token_verifikasi,
            'status' => 'active',
            'signer_name' => 'Kepala Dinas Pendidikan',
            'signer_position' => 'Kepala Dinas',
            'published_at' => now(),
        ]);

        // Submit feedback / saran
        $this->actingAs($peserta)->post(route('peserta.saran.store', $application->id), [
            'saran_peserta' => 'Program magang di Disdik sangat luar biasa dan terstruktur!',
        ])->assertSessionHas('success');

        // Download Transkrip Nilai & Sertifikat
        $this->actingAs($peserta)->get(route('peserta.download.nilai', $application->id))->assertOk();
        $this->actingAs($peserta)->get(route('peserta.sertifikat', $application->id))->assertOk();

        // Verify Certificate publicly
        $resVerify = $this->get(route('certificate.verify', 'TOKENTEST999999'));
        $resVerify->assertOk();
        $resVerify->assertSee('Peserta ULM Terupdate');
    }

    // ==========================================
    // 3. ADMIN INSTANSI (DINAS) ROLE WORKFLOW & PAGES
    // ==========================================

    public function test_admin_instansi_end_to_end_journey_and_pages(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Perhubungan Banjarmasin',
            'nama_pejabat' => 'Kepala Dinas Perhubungan',
            'nip_pejabat' => '197501012000031001',
            'jabatan_pejabat' => 'Kepala Dinas Perhubungan',
            'kode_unit_kerja' => 'DISHUB-01',
            'alamat' => 'Jl. Kertak Baru',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 200,
        ]);

        $adminDinas = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
            'email_verified_at' => now(),
        ]);
        $adminDinas->assignRole('admin_instansi');

        // Dashboard
        $this->actingAs($adminDinas)->get('/dashboard')->assertRedirect(route('dinas.dashboard'));
        $this->actingAs($adminDinas)->get(route('dinas.dashboard'))->assertOk();

        // 1. Pembimbing Lapangan Management
        $this->actingAs($adminDinas)->get(route('dinas.pembimbing_lapangan.index'))->assertOk();

        $resPL = $this->actingAs($adminDinas)->post(route('dinas.pembimbing_lapangan.store'), [
            'name' => 'Pembimbing Lapangan Dishub',
            'email' => 'pldishub@banjarmasinkota.go.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nip' => '198501012010011005',
            'phone' => '081233445566',
        ]);
        $resPL->assertRedirect(route('dinas.pembimbing_lapangan.index'));

        $pl = User::where('email', 'pldishub@banjarmasinkota.go.id')->first();
        $this->assertNotNull($pl);

        $this->actingAs($adminDinas)->get(route('dinas.pembimbing_lapangan.edit', $pl->id))->assertOk();
        $this->actingAs($adminDinas)->put(route('dinas.pembimbing_lapangan.update', $pl->id), [
            'name' => 'Pembimbing Lapangan Dishub Senior',
            'email' => 'pldishub@banjarmasinkota.go.id',
            'nip' => '198501012010011005',
            'phone' => '081233445577',
        ])->assertRedirect(route('dinas.pembimbing_lapangan.index'));

        // 2. Lowongan Management
        $this->actingAs($adminDinas)->get(route('dinas.lowongan.index'))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.lowongan.create'))->assertOk();

        $resLowongan = $this->actingAs($adminDinas)->post(route('dinas.lowongan.store'), [
            'judul_posisi' => 'Staff Manajemen Lalu Lintas',
            'deskripsi' => 'Monitoring ATCS Dishub',
            'persyaratan' => 'Teknik Sipil / Transportasi / IT',
            'kuota' => 4,
            'status' => 'buka',
            'batas_daftar' => now()->addMonths(1)->toDateString(),
        ]);
        $resLowongan->assertRedirect(route('dinas.lowongan.index'));

        $posisi = InternshipPosition::where('instansi_id', $instansi->id)->first();
        $this->assertNotNull($posisi);

        $this->actingAs($adminDinas)->get(route('dinas.lowongan.edit', $posisi->id))->assertOk();
        $this->actingAs($adminDinas)->put(route('dinas.lowongan.update', $posisi->id), [
            'judul_posisi' => 'Staff Manajemen Lalu Lintas & ATCS',
            'deskripsi' => 'Monitoring ATCS Dishub dan Analisis Traffic',
            'persyaratan' => 'Teknik Sipil / Transportasi / IT',
            'kuota' => 5,
            'status' => 'buka',
            'batas_daftar' => now()->addMonths(1)->toDateString(),
        ])->assertRedirect(route('dinas.lowongan.index'));

        // 3. Pelamar Verification
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        $peserta->assignRole('peserta');

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'status' => 'pending',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(2)->toDateString(),
            'cv_path' => 'documents/dummy_cv.pdf',
            'surat_pengantar_path' => 'documents/dummy_surat.pdf',
        ]);

        $this->actingAs($adminDinas)->get(route('dinas.pelamar'))->assertOk();
        $this->actingAs($adminDinas)->post(route('dinas.pelamar.terima', $app->id))
            ->assertRedirect();
        $this->assertEquals('diterima', $app->fresh()->status_value);

        // 4. Peserta Aktif & Mentoring
        $this->actingAs($adminDinas)->get(route('dinas.peserta.index'))->assertOk();

        // Assign Pembimbing Lapangan
        $this->actingAs($adminDinas)->post(route('dinas.peserta.assign', $app->id), [
            'pembimbing_lapangan_id' => $pl->id,
        ])->assertSessionHas('success');

        // View Intern Logbook & Attendance
        $this->actingAs($adminDinas)->get(route('dinas.peserta.logbook', $app->id))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.peserta.absensi', $app->id))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.peserta.absensi.pdf', $app->id))->assertOk();

        // Validate a logbook entry from Dinas
        $log = DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Monitoring traffic CCTV Dishub',
            'status_validasi' => 'pending',
        ]);
        $this->actingAs($adminDinas)->post(route('dinas.logbook.validasi', $log->id), [
            'status' => 'disetujui',
            'komentar' => 'Bagus, teruskan.',
        ])->assertSessionHas('success');

        // Finish Intern
        $this->actingAs($adminDinas)->post(route('dinas.peserta.selesai', $app->id))
            ->assertSessionHas('success');
        $this->assertEquals('selesai', $app->fresh()->status_value);

        // Grade applicant so certificate can be created
        $app->update(['nilai_rata_rata' => 90.0]);

        // 5. Certificate Creation Form & Store
        $this->actingAs($adminDinas)->get(route('dinas.sertifikat.create', $app->id))->assertOk();
        $resStoreCert = $this->actingAs($adminDinas)->post(route('dinas.sertifikat.store', $app->id), [
            'nomor_sertifikat' => '001/DISHUB-SERTIF/2026',
            'tanggal_sertifikat' => now()->toDateString(),
        ]);
        $resStoreCert->assertOk();

        // 6. Pusat Laporan & All Dinas Reports
        $this->actingAs($adminDinas)->get(route('dinas.laporan.hub'))->assertOk();

        $this->actingAs($adminDinas)->get(route('dinas.laporan.rekap'))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.laporan.rekap.print'))->assertOk();

        $this->actingAs($adminDinas)->get(route('dinas.laporan.kinerja_peserta'))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.laporan.kinerja_peserta.print'))->assertOk();

        $this->actingAs($adminDinas)->get(route('dinas.laporan.beban_pembimbing'))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.laporan.beban_pembimbing.print'))->assertOk();

        $this->actingAs($adminDinas)->get(route('dinas.laporan.demografi_kampus'))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.laporan.demografi_kampus.print'))->assertOk();

        $this->actingAs($adminDinas)->get(route('dinas.laporan.jurnal_harian'))->assertOk();
        $this->actingAs($adminDinas)->get(route('dinas.laporan.jurnal_harian.print'))->assertOk();

        // 7. Pengaturan Pejabat & Instansi Settings
        $this->actingAs($adminDinas)->get(route('dinas.pejabat.edit'))->assertOk();
        $this->actingAs($adminDinas)->put(route('dinas.pejabat.update'), [
            'nama_pejabat' => 'Drs. H. Dishub Baru, M.T',
            'nip_pejabat' => '197501012000031999',
            'jabatan_pejabat' => 'Kepala Dinas Perhubungan',
        ])->assertSessionHas('success');

        $this->actingAs($adminDinas)->get(route('dinas.settings'))->assertOk();
        $this->actingAs($adminDinas)->put(route('dinas.settings.update'), [
            'nama_dinas' => 'Dinas Perhubungan Kota Banjarmasin',
            'alamat' => 'Jl. Kertak Baru Ilir',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 300,
            'jam_mulai_masuk' => '07:30',
            'jam_mulai_pulang' => '16:00',
        ])->assertSessionHas('success');
    }

    // ==========================================
    // 4. PEMBIMBING LAPANGAN (FIELD MENTOR) WORKFLOW & PAGES
    // ==========================================

    public function test_pembimbing_lapangan_end_to_end_journey_and_pages(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Sosial Banjarmasin',
            'kode_unit_kerja' => 'DINSOS-01',
            'alamat' => 'Jl. Pangeran Hidayatullah',
        ]);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $instansi->id,
            'email_verified_at' => now(),
        ]);
        $pl->assignRole('pembimbing_lapangan');

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        $peserta->assignRole('peserta');

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Pekerja Sosial',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'pembimbing_lapangan_id' => $pl->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        // Dashboard
        $this->actingAs($pl)->get('/dashboard')->assertRedirect(route('pembimbing_lapangan.dashboard'));
        $this->actingAs($pl)->get(route('pembimbing_lapangan.dashboard'))->assertOk();

        // Logbook Hub redirects to first active application
        $this->actingAs($pl)->get(route('pembimbing_lapangan.logbook.index'))->assertRedirect(route('pembimbing_lapangan.logbook', $app->id));
        $this->actingAs($pl)->get(route('pembimbing_lapangan.logbook', $app->id))->assertOk();

        // Create a logbook entry and validate it
        $log = DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Pendataan PMKS wilayah Banjarmasin Barat',
            'status_validasi' => 'pending',
        ]);

        $this->actingAs($pl)->post(route('pembimbing_lapangan.logbook.validasi', $log->id), [
            'status' => 'disetujui',
            'komentar' => 'Data valid dan lengkap.',
        ])->assertSessionHas('success');
        $this->assertEquals('disetujui', $log->fresh()->status_validasi);

        // Batch validation test
        $log2 = DailyLog::create([
            'application_id' => $app->id,
            'tanggal' => now()->subDay()->toDateString(),
            'kegiatan' => 'Verifikasi bansos',
            'status_validasi' => 'pending',
        ]);
        $this->actingAs($pl)->post(route('pembimbing_lapangan.logbook.batch_validasi'), [
            'log_ids' => [$log2->id],
            'status' => 'disetujui',
        ])->assertSessionHas('success');
        $this->assertEquals('disetujui', $log2->fresh()->status_validasi);

        // Attendance Monitoring & Validation
        $this->actingAs($pl)->get(route('pembimbing_lapangan.attendance.index'))->assertOk();

        $att = Attendance::create([
            'application_id' => $app->id,
            'date' => now()->toDateString(),
            'status' => 'izin',
            'validation_status' => 'pending',
            'notes' => 'Izin keperluan ujian susulan',
        ]);

        $this->actingAs($pl)->post(route('pembimbing_lapangan.attendance.validate', $att->id), [
            'status_validasi' => 'approved',
            'pembimbing_lapangan_note' => 'Disetujui oleh pembimbing lapangan',
        ])->assertSessionHas('success');
        $this->assertEquals('approved', $att->fresh()->validation_status);

        // Form Penilaian (Grading) & Save
        $this->actingAs($pl)->get(route('pembimbing_lapangan.penilaian', $app->id))->assertOk();
        $this->actingAs($pl)->post(route('pembimbing_lapangan.simpan_nilai', $app->id), [
            'nilai_kerajinan' => 90,
            'nilai_disiplin' => 88,
            'nilai_adaptasi' => 92,
            'nilai_kreatifitas' => 95,
            'nilai_skill_pengetahuan' => 85,
            'catatan_pembimbing_lapangan' => 'Sangat aktif dan teliti.',
        ])->assertRedirect(route('pembimbing_lapangan.dashboard'));

        $this->assertEquals(90.0, (float)$app->fresh()->nilai_rata_rata);
    }

    // ==========================================
    // 5. PEMBIMBING SEKOLAH / KAMPUS (ACADEMIC MENTOR) WORKFLOW & PAGES
    // ==========================================

    public function test_pembimbing_sekolah_end_to_end_journey_and_pages(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas PUPR Banjarmasin',
            'kode_unit_kerja' => 'PUPR-01',
            'alamat' => 'Jl. Brigjend H. Hasan Basri',
        ]);

        $univ = University::create([
            'name' => 'Politeknik Negeri Banjarmasin',
            'city' => 'Banjarmasin',
            'is_active' => true,
        ]);

        $dosen = User::factory()->create([
            'role' => 'pembimbing',
            'university_id' => $univ->id,
            'email_verified_at' => now(),
        ]);
        $dosen->assignRole('pembimbing');

        $mahasiswa = User::factory()->create([
            'role' => 'peserta',
            'university_id' => $univ->id,
            'pembimbing_sekolah_id' => $dosen->id,
            'email_verified_at' => now(),
        ]);
        $mahasiswa->assignRole('peserta');

        $posisi = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Drafter CAD',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = Application::create([
            'user_id' => $mahasiswa->id,
            'internship_position_id' => $posisi->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        // Dashboard
        $this->actingAs($dosen)->get('/dashboard')->assertRedirect(route('pembimbing.dashboard'));
        $this->actingAs($dosen)->get(route('pembimbing.dashboard'))->assertOk();
        $this->actingAs($dosen)->get(route('pembimbing.dashboard'))->assertSee($mahasiswa->name);

        // View Student Logbook
        $this->actingAs($dosen)->get(route('pembimbing.peserta.logbook', $app->id))->assertOk();

        // View Student Attendance
        $this->actingAs($dosen)->get(route('pembimbing.peserta.absensi', $app->id))->assertOk();
    }

    // ==========================================
    // 6. ADMIN KOTA (SUPER ADMIN) WORKFLOW & PAGES
    // ==========================================

    public function test_admin_kota_end_to_end_journey_and_pages(): void
    {
        $adminKota = User::factory()->create([
            'role' => 'admin_kota',
            'email_verified_at' => now(),
        ]);
        $adminKota->assignRole('admin_kota');

        // Dashboard
        $this->actingAs($adminKota)->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($adminKota)->get(route('admin.dashboard'))->assertOk();

        // Audit Trail
        $this->actingAs($adminKota)->get(route('admin.audit_trail'))->assertOk();

        // Instansi Management
        $this->actingAs($adminKota)->get(route('admin.instansi.index'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.instansi.create'))->assertOk();

        $resInstansi = $this->actingAs($adminKota)->post(route('admin.instansi.store'), [
            'nama_dinas' => 'Dinas Lingkungan Hidup Kota Banjarmasin',
            'kode_unit_kerja' => 'DLH-01',
            'alamat' => 'Jl. RE Martadinata',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 250,
            'email_admin' => 'admindlh@banjarmasinkota.go.id',
            'password_admin' => 'password123',
        ]);
        $resInstansi->assertRedirect(route('admin.instansi.index'));

        $dlh = Instansi::where('kode_unit_kerja', 'DLH-01')->first();
        $this->assertNotNull($dlh);

        $this->actingAs($adminKota)->get(route('admin.instansi.edit', $dlh->id))->assertOk();
        $this->actingAs($adminKota)->put(route('admin.instansi.update', $dlh->id), [
            'nama_dinas' => 'Dinas Lingkungan Hidup (DLH) Kota Banjarmasin',
            'kode_unit_kerja' => 'DLH-01',
            'alamat' => 'Jl. RE Martadinata No. 2',
            'latitude' => -3.316694,
            'longitude' => 114.590111,
            'radius_absen' => 300,
            'email_admin' => 'admindlh@banjarmasinkota.go.id',
        ])->assertRedirect(route('admin.instansi.index'));

        // Print Instansi PDF
        $this->actingAs($adminKota)->get(route('admin.instansi.print_pdf'))->assertOk();

        // Master Rumpun Keilmuan (Major Categories) CRUD
        $this->actingAs($adminKota)->get(route('admin.master.major-categories.index'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.master.major-categories.create'))->assertOk();

        $resCat = $this->actingAs($adminKota)->post(route('admin.master.major-categories.store'), [
            'code' => 'EKON',
            'name' => 'Ekonomi, Bisnis & Manajemen',
        ]);
        $resCat->assertRedirect(route('admin.master.major-categories.index'));

        $category = MajorCategory::where('code', 'EKON')->first();
        $this->assertNotNull($category);

        $this->actingAs($adminKota)->get(route('admin.master.major-categories.edit', $category->id))->assertOk();
        $this->actingAs($adminKota)->put(route('admin.master.major-categories.update', $category->id), [
            'code' => 'EKON',
            'name' => 'Ekonomi, Akuntansi, Bisnis & Manajemen',
        ])->assertRedirect(route('admin.master.major-categories.index'));

        // Master Program Studi (Majors) CRUD + Toggle Status
        $this->actingAs($adminKota)->get(route('admin.master.majors.index'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.master.majors.create'))->assertOk();

        $resMajor = $this->actingAs($adminKota)->post(route('admin.master.majors.store'), [
            'major_category_id' => $category->id,
            'name' => 'Manajemen Keuangan',
            'degree_level' => 'S1',
            'is_active' => true,
        ]);
        $resMajor->assertRedirect(route('admin.master.majors.index'));

        $major = Major::where('name', 'Manajemen Keuangan')->first();
        $this->assertNotNull($major);

        $this->actingAs($adminKota)->get(route('admin.master.majors.edit', $major->id))->assertOk();
        $this->actingAs($adminKota)->put(route('admin.master.majors.update', $major->id), [
            'major_category_id' => $category->id,
            'name' => 'Manajemen Keuangan & Perbankan',
            'degree_level' => 'S1',
            'is_active' => true,
        ])->assertRedirect(route('admin.master.majors.index'));

        $this->actingAs($adminKota)->post(route('admin.master.majors.toggle', $major->id))
            ->assertRedirect();
        $this->assertFalse((bool)$major->fresh()->is_active);

        // Certificate Governance
        $posisi = InternshipPosition::create([
            'instansi_id' => $dlh->id,
            'judul_posisi' => 'Staff Pengawas Lingkungan',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        $peserta->assignRole('peserta');

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $posisi->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'nomor_sertifikat' => '001/DLH/2026',
            'token_verifikasi' => 'TOKENGovDLH123',
            'sertifikat_diterbitkan' => true,
            'cv_path' => '-',
            'surat_pengantar_path' => '-',
        ]);

        $cert = Certificate::create([
            'application_id' => $app->id,
            'nomor_sertifikat' => $app->nomor_sertifikat,
            'token_verifikasi' => $app->token_verifikasi,
            'status' => 'active',
            'signer_name' => 'Kepala DLH Banjarmasin',
            'signer_position' => 'Kepala Dinas',
            'published_at' => now(),
        ]);

        $this->actingAs($adminKota)->get(route('admin.certificates.index'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.certificates.show', $cert->id))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.certificates.export_pdf'))->assertOk();

        // Revoke certificate
        $this->actingAs($adminKota)->post(route('admin.certificates.revoke', $cert->id), [
            'revoked_reason' => 'Terdapat kesalahan data peserta terdeteksi.',
        ])->assertRedirect();
        $this->assertEquals('revoked', $cert->fresh()->status);

        // Restore certificate
        $this->actingAs($adminKota)->post(route('admin.certificates.restore', $cert->id))
            ->assertRedirect();
        $this->assertEquals('active', $cert->fresh()->status);

        // User Management CRUD
        $this->actingAs($adminKota)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.users.create'))->assertOk();

        $resUser = $this->actingAs($adminKota)->post(route('admin.users.store'), [
            'name' => 'Admin Instansi Baru',
            'email' => 'adminbaru@banjarmasinkota.go.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin_instansi',
            'instansi_id' => $dlh->id,
        ]);
        $resUser->assertRedirect(route('admin.users.index'));

        $newUser = User::where('email', 'adminbaru@banjarmasinkota.go.id')->first();
        $this->assertNotNull($newUser);

        $this->actingAs($adminKota)->get(route('admin.users.edit', $newUser->id))->assertOk();
        $this->actingAs($adminKota)->put(route('admin.users.update', $newUser->id), [
            'name' => 'Admin Instansi Baru Updated',
            'email' => 'adminbaru@banjarmasinkota.go.id',
            'role' => 'admin_instansi',
            'instansi_id' => $dlh->id,
        ])->assertRedirect(route('admin.users.index'));

        // Monitoring Logbook
        $this->actingAs($adminKota)->get(route('admin.users.logbooks'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.users.logbooks.show', $peserta->id))->assertOk();

        // Super Admin Reports Hub & All PDF Reports
        $this->actingAs($adminKota)->get(route('admin.laporan.hub'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.print'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.peserta_global'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.peserta_global.print'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.instansi'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.grading'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.grading.print'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.instansi_disiplin'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.instansi_disiplin.print'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.durasi_magang'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.durasi_magang.print'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.demografi_jurusan'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.demografi_jurusan.print'))->assertOk();

        $this->actingAs($adminKota)->get(route('admin.laporan.penyerapan_kuota'))->assertOk();
        $this->actingAs($adminKota)->get(route('admin.laporan.penyerapan_kuota.print'))->assertOk();

        // Settings
        $this->actingAs($adminKota)->get(route('admin.settings.index'))->assertOk();
        $this->actingAs($adminKota)->post(route('admin.settings.update'), [
            'app_name' => 'Portal Magang Banjarmasin Hebat',
            'pejabat_name' => 'Walikota Banjarmasin',
            'pejabat_nip' => '196501011990031001',
            'pejabat_jabatan' => 'Walikota',
        ])->assertSessionHas('success');
    }

    // ==========================================
    // 7. SECURITY & ROLE ACCESS CONTROL TESTS
    // ==========================================

    public function test_peserta_cannot_access_other_roles(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $peserta->assignRole('peserta');

        $this->actingAs($peserta)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($peserta)->get(route('dinas.dashboard'))->assertStatus(403);
        $this->actingAs($peserta)->get(route('pembimbing_lapangan.dashboard'))->assertStatus(403);
        $this->actingAs($peserta)->get(route('pembimbing.dashboard'))->assertStatus(403);
    }

    public function test_admin_instansi_cannot_access_unauthorized_areas(): void
    {
        $adminInstansi = User::factory()->create(['role' => 'admin_instansi']);
        $adminInstansi->assignRole('admin_instansi');

        $this->actingAs($adminInstansi)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($adminInstansi)->get(route('peserta.dashboard'))->assertStatus(403);
        $this->actingAs($adminInstansi)->get(route('pembimbing_lapangan.dashboard'))->assertStatus(403);
        $this->actingAs($adminInstansi)->get(route('pembimbing.dashboard'))->assertStatus(403);
    }

    public function test_pembimbing_cannot_access_unauthorized_areas(): void
    {
        $pembimbingLapangan = User::factory()->create(['role' => 'pembimbing_lapangan']);
        $pembimbingLapangan->assignRole('pembimbing_lapangan');

        $this->actingAs($pembimbingLapangan)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($pembimbingLapangan)->get(route('dinas.dashboard'))->assertStatus(403);
        $this->actingAs($pembimbingLapangan)->get(route('peserta.dashboard'))->assertStatus(403);
        $this->actingAs($pembimbingLapangan)->get(route('pembimbing.dashboard'))->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login_on_protected_routes(): void
    {
        $this->get(route('peserta.dashboard'))->assertRedirect(route('login'));
        $this->get(route('dinas.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('pembimbing_lapangan.dashboard'))->assertRedirect(route('login'));
        $this->get(route('pembimbing.dashboard'))->assertRedirect(route('login'));
    }
}
