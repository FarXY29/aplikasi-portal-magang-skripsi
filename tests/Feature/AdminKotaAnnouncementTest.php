<?php

namespace Tests\Feature;

use App\Jobs\SendAnnouncementBroadcastJob;
use App\Mail\AnnouncementBroadcastMail;
use App\Models\Announcement;
use App\Models\BroadcastLog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKotaAnnouncementTest extends TestCase
{
    use DatabaseTransactions;

    private function createAdminKota(): User
    {
        return User::factory()->create([
            'role' => 'admin_kota',
            'email' => 'adminkota_' . uniqid() . '@banjarmasinkota.go.id',
        ]);
    }

    private function createPeserta(): User
    {
        return User::factory()->create([
            'role' => 'peserta',
            'email' => 'peserta_' . uniqid() . '@example.com',
        ]);
    }

    public function test_super_admin_can_access_announcements_index(): void
    {
        $admin = $this->createAdminKota();

        Announcement::create([
            'title' => 'Pengumuman Uji Coba Index',
            'content' => 'Isi pengumuman uji coba index.',
            'type' => 'info',
            'target_audience' => 'all',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.announcements.index'));

        $response->assertOk();
        $response->assertSeeText('Pusat Pengumuman & Broadcast Notifikasi');
        $response->assertSeeText('Pengumuman Uji Coba Index');
    }

    public function test_super_admin_can_create_announcement(): void
    {
        $admin = $this->createAdminKota();

        $response = $this->actingAs($admin)->post(route('admin.announcements.store'), [
            'title' => 'Sosialisasi Jadwal Libur Lebaran',
            'content' => 'Seluruh kegiatan magang diliburkan pada tanggal 1-5 Syawal.',
            'type' => 'event',
            'target_audience' => 'all',
            'is_published' => '1',
            'send_email_broadcast' => '0',
        ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $this->assertDatabaseHas('announcements', [
            'title' => 'Sosialisasi Jadwal Libur Lebaran',
            'type' => 'event',
            'target_audience' => 'all',
            'is_published' => true,
        ]);
    }

    public function test_super_admin_can_create_announcement_with_broadcast_job_dispatch(): void
    {
        Queue::fake();

        $admin = $this->createAdminKota();

        $response = $this->actingAs($admin)->post(route('admin.announcements.store'), [
            'title' => 'Instruksi Pengisian Nilai Akhir',
            'content' => 'Mohon seluruh pembimbing segera mengisi penilaian akhir.',
            'type' => 'urgent',
            'target_audience' => 'pembimbing',
            'is_published' => '1',
            'send_email_broadcast' => '1',
        ]);

        $response->assertRedirect(route('admin.announcements.index'));

        $announcement = Announcement::where('title', 'Instruksi Pengisian Nilai Akhir')->first();
        $this->assertNotNull($announcement);

        Queue::assertPushed(SendAnnouncementBroadcastJob::class, function ($job) use ($announcement) {
            return $job->announcement->id === $announcement->id;
        });

        $this->assertDatabaseHas('broadcast_logs', [
            'announcement_id' => $announcement->id,
            'recipient_role' => 'pembimbing',
            'status' => 'processing',
        ]);
    }

    public function test_super_admin_can_update_announcement(): void
    {
        $admin = $this->createAdminKota();

        $announcement = Announcement::create([
            'title' => 'Judul Awal',
            'content' => 'Konten awal.',
            'type' => 'info',
            'target_audience' => 'peserta',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.announcements.update', $announcement->id), [
            'title' => 'Judul Diperbarui',
            'content' => 'Konten telah direvisi.',
            'type' => 'warning',
            'target_audience' => 'peserta',
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => 'Judul Diperbarui',
            'type' => 'warning',
        ]);
    }

    public function test_super_admin_can_toggle_publish_status(): void
    {
        $admin = $this->createAdminKota();

        $announcement = Announcement::create([
            'title' => 'Status Toggle Test',
            'content' => 'Testing toggle publikasi.',
            'type' => 'info',
            'target_audience' => 'all',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.announcements.toggle_publish', $announcement->id));
        $response->assertSessionHas('success');

        $this->assertFalse($announcement->fresh()->is_published);

        $this->actingAs($admin)->post(route('admin.announcements.toggle_publish', $announcement->id));
        $this->assertTrue($announcement->fresh()->is_published);
    }

    public function test_super_admin_can_delete_announcement(): void
    {
        $admin = $this->createAdminKota();

        $announcement = Announcement::create([
            'title' => 'Pengumuman Dihapus',
            'content' => 'Akan segera dihapus.',
            'type' => 'info',
            'target_audience' => 'all',
            'is_published' => false,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.announcements.destroy', $announcement->id));

        $response->assertRedirect(route('admin.announcements.index'));
        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }

    public function test_super_admin_can_manually_trigger_broadcast(): void
    {
        Queue::fake();

        $admin = $this->createAdminKota();

        $announcement = Announcement::create([
            'title' => 'Pengumuman Broadcast Manual',
            'content' => 'Broadcast dipicu secara manual.',
            'type' => 'info',
            'target_audience' => 'peserta',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.announcements.broadcast', $announcement->id));
        $response->assertSessionHas('success');

        Queue::assertPushed(SendAnnouncementBroadcastJob::class);
    }

    public function test_non_admin_cannot_access_announcement_crud(): void
    {
        $peserta = $this->createPeserta();

        $response = $this->actingAs($peserta)->get(route('admin.announcements.index'));
        $response->assertForbidden();
    }

    public function test_announcement_broadcast_job_and_mail_execution(): void
    {
        Mail::fake();

        $admin = $this->createAdminKota();
        $peserta = $this->createPeserta();

        $announcement = Announcement::create([
            'title' => 'Uji Job Broadcast Mail',
            'content' => 'Tes pengiriman mailable resmi.',
            'type' => 'urgent',
            'target_audience' => 'peserta',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $log = BroadcastLog::create([
            'announcement_id' => $announcement->id,
            'recipient_role' => 'peserta',
            'status' => 'queued',
        ]);

        $job = new SendAnnouncementBroadcastJob($announcement, $log);
        $job->handle();

        Mail::assertSent(AnnouncementBroadcastMail::class, function ($mail) use ($peserta) {
            return $mail->recipient->id === $peserta->id;
        });

        $this->assertEquals('completed', $log->fresh()->status);
        $this->assertNotNull($log->fresh()->sent_at);
        $this->assertTrue($announcement->fresh()->send_email_broadcast);
    }

    public function test_announcement_appears_on_admin_instansi_dashboard(): void
    {
        $admin = $this->createAdminKota();
        $instansi = \App\Models\Instansi::create([
            'nama_dinas' => 'Dinas Test ' . uniqid(),
            'kode_unit_kerja' => 'TEST-' . uniqid(),
            'alamat' => 'Alamat Test',
            'jam_mulai_masuk' => '08:00:00',
            'jam_mulai_pulang' => '16:00:00',
            'max_total_quota' => 10,
        ]);

        $dinasUser = User::factory()->create([
            'role' => 'admin_instansi',
            'instansi_id' => $instansi->id,
            'email' => 'dinas_' . uniqid() . '@example.com',
        ]);

        Announcement::create([
            'title' => 'Pengumuman Penting Khusus Dinas OPD',
            'content' => 'Isi pengumuman penting untuk dinas.',
            'type' => 'urgent',
            'target_audience' => 'admin_instansi',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($dinasUser)->get(route('dinas.dashboard'));
        $response->assertOk();
        $response->assertSeeText('Pengumuman Penting Khusus Dinas OPD');
    }
}

