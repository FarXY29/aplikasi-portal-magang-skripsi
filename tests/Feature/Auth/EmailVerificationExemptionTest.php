<?php

namespace Tests\Feature\Auth;

use App\Models\Instansi;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationExemptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_does_not_need_email_verification_and_can_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_kota',
            'email_verified_at' => null, // Should be auto-set or bypassed
        ]);

        $this->assertTrue($admin->isEmailVerificationExempt());
        $this->assertTrue($admin->hasVerifiedEmail());

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_instansi_does_not_need_email_verification_and_can_access_dashboard(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kominfo',
            'kode_unit_kerja' => 'KOMINFO-01',
            'alamat' => 'Jl. Pangeran Samudra',
            'latitude' => -3.32,
            'longitude' => 114.59,
            'radius_absen' => 50,
        ]);

        $adminInstansi = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
            'email_verified_at' => null,
        ]);

        $this->assertTrue($adminInstansi->isEmailVerificationExempt());
        $this->assertTrue($adminInstansi->hasVerifiedEmail());

        $response = $this->actingAs($adminInstansi)->get('/dashboard');
        $response->assertRedirect(route('dinas.dashboard'));
    }

    public function test_pembimbing_lapangan_does_not_need_email_verification_and_can_access_dashboard(): void
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kesehatan',
            'kode_unit_kerja' => 'DINKES-01',
            'alamat' => 'Jl. Kesehatan',
            'latitude' => -3.33,
            'longitude' => 114.60,
            'radius_absen' => 50,
        ]);

        $pl = User::factory()->create([
            'role' => 'pembimbing_lapangan',
            'instansi_id' => $instansi->id,
            'email_verified_at' => null,
        ]);

        $this->assertTrue($pl->isEmailVerificationExempt());
        $this->assertTrue($pl->hasVerifiedEmail());

        $response = $this->actingAs($pl)->get('/dashboard');
        $response->assertRedirect(route('pembimbing_lapangan.dashboard'));
    }

    public function test_peserta_requires_email_verification(): void
    {
        // Unverified peserta
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => null,
        ]);

        $this->assertFalse($peserta->isEmailVerificationExempt());
        $this->assertFalse($peserta->hasVerifiedEmail());

        // Accessing dashboard should redirect to verification notice
        $response = $this->actingAs($peserta)->get('/dashboard');
        $response->assertRedirect(route('verification.notice'));

        // Verified peserta
        $peserta->email_verified_at = now();
        $peserta->save();

        $this->assertTrue($peserta->hasVerifiedEmail());
        $responseVerified = $this->actingAs($peserta)->get('/dashboard');
        $responseVerified->assertRedirect(route('peserta.dashboard'));
    }

    public function test_academic_supervisor_pembimbing_requires_email_verification(): void
    {
        // Unverified pembimbing
        $pembimbing = User::factory()->create([
            'role' => 'pembimbing',
            'email_verified_at' => null,
        ]);

        $this->assertFalse($pembimbing->isEmailVerificationExempt());
        $this->assertFalse($pembimbing->hasVerifiedEmail());

        // Accessing dashboard should redirect to verification notice
        $response = $this->actingAs($pembimbing)->get('/dashboard');
        $response->assertRedirect(route('verification.notice'));

        // Verified pembimbing
        $pembimbing->email_verified_at = now();
        $pembimbing->save();

        $this->assertTrue($pembimbing->hasVerifiedEmail());
        $responseVerified = $this->actingAs($pembimbing)->get('/dashboard');
        $responseVerified->assertRedirect(route('pembimbing.dashboard'));
    }

    public function test_creating_exempt_roles_automatically_sets_email_verified_at(): void
    {
        $admin = User::create([
            'name' => 'Super Admin Test',
            'username' => 'superadmin_test',
            'email' => 'superadmin_test@test.local',
            'password' => bcrypt('password'),
            'role' => 'admin_kota',
        ]);

        $this->assertNotNull($admin->email_verified_at);

        $pl = User::create([
            'name' => 'PL Test',
            'username' => 'pl_test',
            'email' => 'pl_test@test.local',
            'password' => bcrypt('password'),
            'role' => 'pembimbing_lapangan',
        ]);

        $this->assertNotNull($pl->email_verified_at);
    }

    public function test_send_email_verification_notification_is_suppressed_for_exempt_roles(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin_kota']);
        $admin->sendEmailVerificationNotification();
        Notification::assertNothingSent();

        $pl = User::factory()->create(['role' => 'pembimbing_lapangan']);
        $pl->sendEmailVerificationNotification();
        Notification::assertNothingSent();

        $peserta = User::factory()->create(['role' => 'peserta', 'email_verified_at' => null]);
        $peserta->sendEmailVerificationNotification();
        Notification::assertSentTo($peserta, VerifyEmail::class);
    }

    public function test_profile_update_maintains_verified_status_for_exempt_roles_and_resets_for_peserta(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_kota',
            'email' => 'old_admin@test.local',
            'email_verified_at' => now(),
        ]);

        $responseAdmin = $this->actingAs($admin)->patch('/profile', [
            'name' => 'Updated Admin',
            'email' => 'new_admin@test.local',
        ]);

        $responseAdmin->assertSessionHasNoErrors();
        $this->assertNotNull($admin->fresh()->email_verified_at);
        $this->assertEquals('new_admin@test.local', $admin->fresh()->email);

        $peserta = User::factory()->create([
            'role' => 'peserta',
            'email' => 'old_peserta@test.local',
            'email_verified_at' => now(),
            'asal_instansi' => 'SMKN 1 Banjarmasin',
        ]);

        $responsePeserta = $this->actingAs($peserta)->patch('/profile', [
            'name' => 'Updated Peserta',
            'email' => 'new_peserta@test.local',
            'asal_instansi' => 'SMKN 1 Banjarmasin',
        ]);

        $responsePeserta->assertSessionHasNoErrors();
        $this->assertNull($peserta->fresh()->email_verified_at);
        $this->assertEquals('new_peserta@test.local', $peserta->fresh()->email);
    }
}

