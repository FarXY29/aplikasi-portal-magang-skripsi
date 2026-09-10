<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InternshipCompleted extends Mailable implements ShouldQueue, ShouldQueueAfterCommit
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;
    public array $backoff = [10, 60, 300];

    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
        $this->afterCommit();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Magang Anda Telah Selesai - '.config('app.name', 'Portal Magang'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.internship_completed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Pengiriman email kelulusan magang gagal diproses di antrean.', [
            'application_id' => is_object($this->application) ? ($this->application->id ?? null) : null,
            'user_id' => is_object($this->application) ? ($this->application->user_id ?? null) : null,
            'exception' => $exception->getMessage(),
        ]);
    }
}
