<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InternshipEndingMail extends Mailable implements ShouldQueue, ShouldQueueAfterCommit
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;
    public array $backoff = [10, 60, 300];

    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application)
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
            subject: 'Peringatan: Masa Magang Berakhir dalam 7 Hari',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.internship.ending',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
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
        Log::error('Pengiriman email peringatan masa magang berakhir gagal diproses di antrean.', [
            'application_id' => $this->application->id ?? null,
            'user_id' => $this->application->user_id ?? null,
            'exception' => $exception->getMessage(),
        ]);
    }
}
