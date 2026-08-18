<?php

namespace App\Mail;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementBroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public Announcement $announcement;
    public User $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(Announcement $announcement, User $recipient)
    {
        $this->announcement = $announcement;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $typePrefix = match ($this->announcement->type) {
            'urgent' => '[PENTING & MENDESAK]',
            'warning' => '[PERINGATAN RESMI]',
            'event' => '[AGENDA KEGIATAN]',
            default => '[PENGUMUMAN RESMI]',
        };

        return new Envelope(
            subject: "{$typePrefix} {$this->announcement->title} - Pemerintah Kota Banjarmasin",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement_broadcast',
            with: [
                'announcement' => $this->announcement,
                'recipient' => $this->recipient,
            ],
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
}
