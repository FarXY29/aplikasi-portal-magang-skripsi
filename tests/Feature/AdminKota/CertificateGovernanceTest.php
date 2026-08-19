<?php

namespace Tests\Feature\AdminKota;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CertificateGovernanceTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected function createCertificateFixture(): Certificate
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Uji Sertifikasi',
            'kode_unit_kerja' => 'INST-TEST-CERT-' . uniqid(),
            'alamat' => 'Jl. Uji Sertifikat',
            'latitude' => -3.31,
            'longitude' => 114.59,
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Uji Sertifikat',
            'kuota' => 5,
            'status' => 'buka',
            'deskripsi' => 'Deskripsi posisi magang uji sertifikat.',
        ]);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'name' => 'Peserta Uji Sertifikat',
            'nik' => '6371999999999999',
            'asal_instansi' => 'Universitas Uji',
        ]);

        $app = Application::create([
            'user_id' => $peserta->id,
            'internship_position_id' => $position->id,
            'status' => 'selesai',
            'tanggal_mulai' => now()->subMonths(3),
            'tanggal_selesai' => now(),
            'nilai_angka' => 88.5,
            'nomor_sertifikat' => 'MG-TEST-' . uniqid(),
            'token_verifikasi' => 'TOKEN_TEST_CERT_' . uniqid(),
            'cv_path' => 'dummy/cv.pdf',
            'surat_pengantar_path' => 'dummy/surat.pdf',
        ]);

        return Certificate::create([
            'application_id' => $app->id,
            'nomor_sertifikat' => $app->nomor_sertifikat,
            'token_verifikasi' => $app->token_verifikasi,
            'status' => 'active',
            'signer_name' => 'Kepala Dinas Uji',
            'signature_mock' => hash('sha256', 'test_signature'),
            'published_at' => now(),
        ]);
    }

    public function test_guest_and_non_admin_cannot_access_certificate_registry(): void
    {
        $response = $this->get(route('admin.certificates.index'));
        $response->assertRedirect(route('login'));

        $peserta = User::factory()->create(['role' => 'peserta']);
        $response = $this->actingAs($peserta)->get(route('admin.certificates.index'));
        $response->assertStatus(403);
    }

    public function test_admin_kota_can_view_certificate_registry(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $cert = $this->createCertificateFixture();

        $response = $this->actingAs($admin)->get(route('admin.certificates.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Registri & Tata Kelola Sertifikat Kota');
        $response->assertSee($cert->nomor_sertifikat);
    }

    public function test_admin_kota_can_revoke_and_restore_certificate(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $cert = $this->createCertificateFixture();

        // 1. Revoke Certificate
        $revokeResponse = $this->actingAs($admin)->post(route('admin.certificates.revoke', $cert->id), [
            'revoked_reason' => 'Ditemukan pemalsuan dokumen absensi oleh peserta.',
        ]);

        $revokeResponse->assertSessionHas('success');
        $cert->refresh();

        $this->assertTrue($cert->isRevoked());
        $this->assertEquals('Ditemukan pemalsuan dokumen absensi oleh peserta.', $cert->revoked_reason);
        $this->assertEquals($admin->id, $cert->revoked_by);

        // 2. Public Verify route reflects revocation
        $publicVerifyResponse = $this->get(route('certificate.verify', $cert->token_verifikasi));
        $publicVerifyResponse->assertStatus(200);
        $publicVerifyResponse->assertSeeText('Sertifikat Dibatalkan');
        $publicVerifyResponse->assertSeeText('Ditemukan pemalsuan dokumen absensi oleh peserta.');

        // 3. Restore Certificate
        $restoreResponse = $this->actingAs($admin)->post(route('admin.certificates.restore', $cert->id));
        $restoreResponse->assertSessionHas('success');
        $cert->refresh();

        $this->assertTrue($cert->isActive());
        $this->assertNull($cert->revoked_at);
        $this->assertNull($cert->revoked_reason);
    }

    public function test_admin_kota_can_export_certificates_register_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin_kota']);

        $this->createCertificateFixture();

        $response = $this->actingAs($admin)->get(route('admin.certificates.export_pdf'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
