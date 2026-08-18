<?php

namespace Tests\Feature;

use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;
use App\Mail\InternshipCompleted;
use App\Mail\InternshipEndingMail;
use App\Models\Application;
use App\Models\Instansi;
use App\Models\InternshipPosition;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailViewsTest extends TestCase
{
    use DatabaseTransactions;

    private function makeApplication(?User $participant = null, ?InternshipPosition $position = null): Application
    {
        $participant ??= User::factory()->create(['role' => 'peserta']);
        $position ??= $this->makePosition();

        return Application::create([
            'user_id' => $participant->id,
            'internship_position_id' => $position->id,
            'cv_path' => 'cv.pdf',
            'surat_pengantar_path' => 'surat.pdf',
            'status' => 'diterima',
            'tanggal_mulai' => now()->subMonth()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'catatan_pembimbing_lapangan' => 'Pertahankan kinerja yang baik.',
        ]);
    }

    private function makePosition(): InternshipPosition
    {
        $instansi = Instansi::create([
            'nama_dinas' => 'Dinas Pendidikan Test',
            'alamat' => 'Banjarmasin',
            'kode_unit_kerja' => 'DINDIK-TEST',
        ]);

        return InternshipPosition::create([
            'instansi_id' => $instansi->id,
            'judul_posisi' => 'Staf Administrasi',
            'kuota' => 1,
            'status' => 'buka',
        ]);
    }

    public function test_application_accepted_mail_renders(): void
    {
        Mail::fake();
        $app = $this->makeApplication();

        Mail::to($app->user->email)->send(new ApplicationAcceptedMail($app));

        Mail::assertSent(ApplicationAcceptedMail::class, function (ApplicationAcceptedMail $mail) use ($app) {
            $html = $mail->render();

            $this->assertStringContainsString($app->user->name, $html);
            $this->assertStringContainsString('Dinas Pendidikan Test', $html);
            $this->assertStringContainsString('Staf Administrasi', $html);

            return true;
        });
    }

    public function test_application_rejected_mail_renders(): void
    {
        Mail::fake();
        $app = $this->makeApplication();

        Mail::to($app->user->email)->send(new ApplicationRejectedMail($app));

        Mail::assertSent(ApplicationRejectedMail::class, function (ApplicationRejectedMail $mail) use ($app) {
            $html = $mail->render();

            $this->assertStringContainsString($app->user->name, $html);
            $this->assertStringContainsString('BELUM DAPAT DITERIMA', $html);
            $this->assertStringContainsString('Dinas Pendidikan Test', $html);

            return true;
        });
    }

    public function test_internship_ending_mail_renders(): void
    {
        Mail::fake();
        $app = $this->makeApplication();

        Mail::to($app->user->email)->send(new InternshipEndingMail($app));

        Mail::assertSent(InternshipEndingMail::class, function (InternshipEndingMail $mail) use ($app) {
            $html = $mail->render();

            $this->assertStringContainsString('7 hari ke depan', $html);
            $this->assertStringContainsString('Logbook', $html);

            return true;
        });
    }

    public function test_internship_completed_mail_renders(): void
    {
        Mail::fake();
        $app = $this->makeApplication();

        Mail::to($app->user->email)->send(new InternshipCompleted($app));

        Mail::assertSent(InternshipCompleted::class, function (InternshipCompleted $mail) use ($app) {
            $html = $mail->render();

            $this->assertStringContainsString($app->user->name, $html);
            $this->assertStringContainsString('Selesai & Lulus', $html);
            $this->assertStringContainsString('Pertahankan kinerja yang baik.', $html);

            return true;
        });
    }

    public function test_application_accepted_mail_handles_missing_position(): void
    {
        Mail::fake();
        $app = $this->makeApplication();
        // Simulate a queued mailable whose Application->position was deleted
        // after serialization (e.g. instansi cascade delete). Eloquent returns
        // the cached null relation without re-querying, exercising the ?-> path.
        $app->setRelation('position', null);

        Mail::to($app->user->email)->send(new ApplicationAcceptedMail($app));

        Mail::assertSent(ApplicationAcceptedMail::class, function (ApplicationAcceptedMail $mail) {
            $html = $mail->render();
            $this->assertStringContainsString('-', $html);

            return true;
        });
    }
}
