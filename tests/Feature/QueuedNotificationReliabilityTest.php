<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Listeners\LogFailedQueueJob;
use App\Listeners\LogNotificationFailure;
use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;
use App\Mail\InternshipCompleted;
use App\Mail\InternshipEndingMail;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use App\Notifications\ApplicationStatusNotification;
use App\Services\ApplicationStateTransitionService;
use Carbon\Carbon;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Tests for Phase 2B: Notification & Reliability
 * - P1-2101: Queued Notifications & Transaction Consistency
 * - P1-2102: Notification Failure Observability
 */
class QueuedNotificationReliabilityTest extends TestCase
{
    use DatabaseTransactions;

    private ApplicationStateTransitionService $transitionService;
    private User $peserta;
    private User $adminInstansi;
    private Instansi $instansi;
    private InternshipPosition $position;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('application_timelines') || !Schema::hasTable('attendance_disputes')) {
            Artisan::call('migrate');
        }

        $this->seed(RoleAndPermissionSeeder::class);

        $this->transitionService = app(ApplicationStateTransitionService::class);

        $this->instansi = Instansi::factory()->create([
            'nama_dinas' => 'Dinas Perhubungan Test',
            'max_total_quota' => 10,
        ]);

        $this->position = InternshipPosition::factory()->create([
            'instansi_id' => $this->instansi->id,
            'kuota' => 5,
        ]);

        $this->adminInstansi = User::factory()->create([
            'instansi_id' => $this->instansi->id,
            'role' => 'admin_instansi',
        ]);
        $this->adminInstansi->assignRole('admin_instansi');

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'email' => 'peserta.test@example.com',
        ]);
        $this->peserta->assignRole('peserta');
    }

    private function createTestApplication(string $status = 'pending', array $overrides = []): Application
    {
        return Application::factory()->create(array_merge([
            'user_id' => $this->peserta->id,
            'internship_position_id' => $this->position->id,
            'status' => $status,
            'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::now()->addDays(35)->format('Y-m-d'),
        ], $overrides));
    }

    public function test_all_mailables_and_notifications_implement_should_queue(): void
    {
        $this->assertInstanceOf(ShouldQueue::class, new ApplicationAcceptedMail($this->createTestApplication()));
        $this->assertInstanceOf(ShouldQueue::class, new ApplicationRejectedMail($this->createTestApplication()));
        $this->assertInstanceOf(ShouldQueue::class, new InternshipCompleted($this->createTestApplication()));
        $this->assertInstanceOf(ShouldQueue::class, new InternshipEndingMail($this->createTestApplication()));
        $this->assertInstanceOf(ShouldQueue::class, new ApplicationStatusNotification(
            $this->createTestApplication(),
            'Test',
            'Test message'
        ));
    }

    public function test_queued_items_have_reliable_retry_and_after_commit_configs(): void
    {
        $classes = [
            ApplicationAcceptedMail::class,
            ApplicationRejectedMail::class,
            InternshipCompleted::class,
            InternshipEndingMail::class,
        ];

        $app = $this->createTestApplication();

        foreach ($classes as $class) {
            $instance = new $class($app);
            $this->assertSame(3, $instance->tries);
            $this->assertSame(60, $instance->timeout);
            $this->assertSame([10, 60, 300], $instance->backoff);
            $this->assertTrue($instance->afterCommit);
        }

        $notification = new ApplicationStatusNotification($app, 'Test', 'Test msg');
        $this->assertSame(3, $notification->tries);
        $this->assertSame(60, $notification->timeout);
        $this->assertSame([10, 60, 300], $notification->backoff);
        $this->assertTrue($notification->afterCommit);
    }

    public function test_accept_applicant_queues_email_and_database_notification(): void
    {
        Mail::fake();
        Notification::fake();

        $app = $this->createTestApplication('pending');

        $this->transitionService->accept($app, $this->adminInstansi->id);

        Mail::assertQueued(ApplicationAcceptedMail::class, function (ApplicationAcceptedMail $mail) use ($app) {
            return $mail->application->id === $app->id && $mail->hasTo($this->peserta->email);
        });

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusNotification::class,
            function (ApplicationStatusNotification $notification) use ($app) {
                return $notification->application->id === $app->id
                    && $notification->type === 'success'
                    && str_contains($notification->title, 'Lamaran Diterima');
            }
        );
    }

    public function test_reject_applicant_queues_email_and_database_notification(): void
    {
        Mail::fake();
        Notification::fake();

        $app = $this->createTestApplication('pending');

        $this->transitionService->reject($app, 'Kualifikasi belum sesuai.', $this->adminInstansi->id);

        Mail::assertQueued(ApplicationRejectedMail::class, function (ApplicationRejectedMail $mail) use ($app) {
            return $mail->application->id === $app->id && $mail->hasTo($this->peserta->email);
        });

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusNotification::class,
            function (ApplicationStatusNotification $notification) use ($app) {
                return $notification->application->id === $app->id
                    && $notification->type === 'danger'
                    && str_contains($notification->message, 'Kualifikasi belum sesuai');
            }
        );
    }

    public function test_finish_internship_queues_completed_email_and_notification(): void
    {
        Mail::fake();
        Notification::fake();

        $app = $this->createTestApplication('diterima');

        $this->transitionService->finish($app, $this->adminInstansi->id);

        Mail::assertQueued(InternshipCompleted::class, function (InternshipCompleted $mail) use ($app) {
            return $mail->application->id === $app->id && $mail->hasTo($this->peserta->email);
        });

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusNotification::class,
            function (ApplicationStatusNotification $notification) use ($app) {
                return $notification->application->id === $app->id
                    && $notification->type === 'success'
                    && str_contains($notification->title, 'Magang Telah Selesai');
            }
        );
    }

    public function test_cancel_application_queues_cancellation_notification(): void
    {
        Notification::fake();

        $app = $this->createTestApplication('pending');

        $this->transitionService->cancel($app, 'Alasan pribadi', $this->peserta->id, isParticipant: true);

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusNotification::class,
            function (ApplicationStatusNotification $notification) use ($app) {
                return $notification->application->id === $app->id
                    && $notification->type === 'warning'
                    && str_contains($notification->title, 'Lamaran Dibatalkan');
            }
        );
    }

    public function test_send_ending_notifications_command_queues_both_mail_and_in_app_notification(): void
    {
        Mail::fake();
        Notification::fake();

        $targetDate = Carbon::now()->addDays(7)->toDateString();

        $app = $this->createTestApplication('diterima', [
            'tanggal_mulai' => Carbon::now()->subDays(23)->toDateString(),
            'tanggal_selesai' => $targetDate,
        ]);

        $this->artisan('app:send-ending-notifications')
            ->expectsOutputToContain('Berhasil mendispatch 1 email & notifikasi')
            ->assertExitCode(0);

        Mail::assertQueued(InternshipEndingMail::class, function (InternshipEndingMail $mail) use ($app) {
            return $mail->application->id === $app->id && $mail->hasTo($this->peserta->email);
        });

        Notification::assertSentTo(
            $this->peserta,
            ApplicationStatusNotification::class,
            function (ApplicationStatusNotification $notification) use ($app) {
                return $notification->application->id === $app->id
                    && $notification->type === 'warning'
                    && str_contains($notification->title, 'Peringatan Masa Magang Berakhir');
            }
        );
    }

    public function test_notification_and_mailable_failed_methods_log_structured_errors_without_secrets(): void
    {
        $app = $this->createTestApplication();
        $dummyException = new \RuntimeException('SMTP Connection Timeout');

        Log::shouldReceive('error')
            ->once()
            ->with(
                \Mockery::pattern('/Pengiriman email penerimaan magang gagal/'),
                \Mockery::on(function (array $context) use ($app) {
                    $this->assertSame($app->id, $context['application_id']);
                    $this->assertSame($app->user_id, $context['user_id']);
                    $this->assertStringContainsString('SMTP Connection Timeout', $context['exception']);
                    $this->assertArrayNotHasKey('password', $context);
                    $this->assertArrayNotHasKey('token', $context);
                    return true;
                })
            );

        $acceptedMail = new ApplicationAcceptedMail($app);
        $acceptedMail->failed($dummyException);

        Log::shouldReceive('error')
            ->once()
            ->with(
                \Mockery::pattern('/Notifikasi status lamaran gagal/'),
                \Mockery::on(function (array $context) use ($app) {
                    $this->assertSame($app->id, $context['application_id']);
                    $this->assertSame($app->user_id, $context['user_id']);
                    $this->assertStringContainsString('SMTP Connection Timeout', $context['exception']);
                    $this->assertArrayNotHasKey('password', $context);
                    $this->assertArrayNotHasKey('token', $context);
                    return true;
                })
            );

        $notification = new ApplicationStatusNotification($app, 'Test Title', 'Test Body');
        $notification->failed($dummyException);
    }

    public function test_log_notification_failure_listener_handles_notification_failed_event(): void
    {
        $app = $this->createTestApplication();
        $notification = new ApplicationStatusNotification($app, 'Fail Title', 'Fail Message');

        Log::shouldReceive('error')
            ->once()
            ->with(
                'Notifikasi gagal dikirim ke channel.',
                \Mockery::on(function (array $context) use ($app) {
                    $this->assertSame('database', $context['channel']);
                    $this->assertSame(ApplicationStatusNotification::class, $context['notification']);
                    $this->assertSame($app->id, $context['application_id']);
                    $this->assertSame($this->peserta->id, $context['notifiable_id']);
                    return true;
                })
            );

        $event = new NotificationFailed(
            $this->peserta,
            $notification,
            'database',
            ['sample_data' => 'test']
        );

        $listener = new LogNotificationFailure();
        $listener->handle($event);
    }

    public function test_log_failed_queue_job_listener_handles_job_failed_event(): void
    {
        $jobMock = \Mockery::mock(\Illuminate\Contracts\Queue\Job::class);
        $jobMock->shouldReceive('resolveName')->andReturn('App\Mail\ApplicationAcceptedMail');
        $jobMock->shouldReceive('getQueue')->andReturn('default');
        $jobMock->shouldReceive('attempts')->andReturn(3);

        $exception = new \Exception('Queue max attempts reached');

        Log::shouldReceive('error')
            ->once()
            ->with(
                \Mockery::pattern('/Queue job gagal diproses/'),
                \Mockery::on(function (array $context) {
                    $this->assertSame('database', $context['connection']);
                    $this->assertSame('default', $context['queue']);
                    $this->assertSame('App\Mail\ApplicationAcceptedMail', $context['job']);
                    $this->assertSame(3, $context['attempts']);
                    $this->assertSame('Queue max attempts reached', $context['exception_message']);
                    return true;
                })
            );

        $event = new JobFailed('database', $jobMock, $exception);

        $listener = new LogFailedQueueJob();
        $listener->handle($event);
    }

    public function test_log_notification_failure_listener_handles_anonymous_notifiable_without_crashing(): void
    {
        $app = $this->createTestApplication();
        $notification = new ApplicationStatusNotification($app, 'Fail Anonymous', 'Fail Message');

        $anonymousNotifiable = new \Illuminate\Notifications\AnonymousNotifiable();

        Log::shouldReceive('error')
            ->once()
            ->with(
                'Notifikasi gagal dikirim ke channel.',
                \Mockery::on(function (array $context) {
                    $this->assertSame('mail', $context['channel']);
                    $this->assertSame('Illuminate\Notifications\AnonymousNotifiable', $context['notifiable_type']);
                    $this->assertNull($context['notifiable_id']);
                    return true;
                })
            );

        $event = new NotificationFailed(
            $anonymousNotifiable,
            $notification,
            'mail',
            []
        );

        $listener = new LogNotificationFailure();
        $listener->handle($event);
    }

    public function test_application_status_notification_payload_and_serialization(): void
    {
        $app = $this->createTestApplication('pending');
        $notification = new ApplicationStatusNotification(
            $app,
            'Test Judul',
            'Test Pesan',
            'info'
        );

        $payload = $notification->toArray($this->peserta);

        $this->assertSame($app->id, $payload['application_id']);
        $this->assertSame('pending', $payload['status']);
        $this->assertSame($app->nomor_registrasi, $payload['nomor_registrasi']);
        $this->assertSame('Test Judul', $payload['title']);
        $this->assertSame('Test Pesan', $payload['message']);
        $this->assertSame('info', $payload['type']);
        $this->assertSame($this->position->judul_posisi, $payload['position_title']);
        $this->assertSame($this->instansi->nama_dinas, $payload['instansi_name']);

        // Verify clean PHP serialization/deserialization with SerializesModels
        $serialized = serialize($notification);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(ApplicationStatusNotification::class, $unserialized);
        $this->assertSame($app->id, $unserialized->application->id);
    }
}

