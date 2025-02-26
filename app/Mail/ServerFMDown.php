<?php

namespace App\Mail;

use App\Models\Database;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServerFMDown extends Mailable
{
    use Queueable, SerializesModels;

    protected $database;
    protected $error;

    /**
     * Create a new message instance.
     */
    public function __construct(Database $db, string $error)
    {
        $this->database = $db;
        $this->error = $error;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Server {$this->database->name} Down",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.server-f-m-down',
            with: [
                'database' => $this->database,
                'error' => $this->error
            ]
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
