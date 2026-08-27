<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Notifications\ApplicationStatusNotification;
use App\Services\InternshipApplicationService;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_cannot_access_notification_endpoints()
    {
        $response = $this->getJson(route('notifications.unread'));
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_fetch_unread_notifications()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Lingkungan Hidup',
            'kode_unit_kerja' => 'DLH-' . uniqid(),
            'alamat' => 'Jl. Pangeran Hidayatullah',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff GIS',
            'kuota' => 3,
            'status' => 'buka',
        ]);

        $app = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'diterima',
            'tanggal_mulai' => now()->addDays(3)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(1)->format('Y-m-d'),
        ]);

        $user->notify(new ApplicationStatusNotification(
            $app,
            'Lamaran Diterima!',
            'Selamat lamaran Anda telah disetujui.',
            'success'
        ));

        $this->actingAs($user);

        $response = $this->getJson(route('notifications.unread'));
        $response->assertStatus(200);
        $response->assertJson([
            'unread_count' => 1,
        ]);
        $response->assertJsonFragment([
            'title' => 'Lamaran Diterima!',
        ]);
    }

    public function test_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Kesehatan',
            'kode_unit_kerja' => 'DINKES-' . uniqid(),
            'alamat' => 'Jl. Tirta Dharma',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Asisten Rekam Medis',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'pending',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(1)->format('Y-m-d'),
        ]);

        $user->notify(new ApplicationStatusNotification(
            $app,
            'Status Berkas',
            'Berkas dalam peninjauan.',
            'info'
        ));

        $this->actingAs($user);

        $notificationId = $user->unreadNotifications()->first()->id;

        $response = $this->postJson(route('notifications.read', $notificationId));
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Sosial',
            'kode_unit_kerja' => 'DINSOS-' . uniqid(),
            'alamat' => 'Jl. Mayjen Sutoyo S',
        ]);

        $position = InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staff Administrasi',
            'kuota' => 2,
            'status' => 'buka',
        ]);

        $app = Application::create([
            'user_id' => $user->id,
            'internship_position_id' => $position->id,
            'status' => 'pending',
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addMonths(1)->format('Y-m-d'),
        ]);

        $user->notify(new ApplicationStatusNotification($app, 'Notif 1', 'Pesan 1', 'info'));
        $user->notify(new ApplicationStatusNotification($app, 'Notif 2', 'Pesan 2', 'info'));

        $this->assertEquals(2, $user->unreadNotifications()->count());

        $this->actingAs($user);

        $response = $this->postJson(route('notifications.read_all'));
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(0, $user->fresh()->unreadNotifications()->count());
    }
}
