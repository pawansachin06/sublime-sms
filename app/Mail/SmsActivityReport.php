<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SmsActivityReport extends Mailable
{
    use Queueable, SerializesModels;

    public $csvFilePath;
    public $frequency;

    /**
     * Create a new message instance.
     */
    public function __construct($csvFilePath, $frequency)
    {
        if (!empty($csvFilePath)) {
            $this->csvFilePath = storage_path('app/' . $csvFilePath);
        } else {
            $this->csvFilePath = '';
        }
        $this->frequency = $frequency;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SMS activity '. $this->frequency .' report '. date('j F Y, h:i:s e'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sms-activity-report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (!empty($this->csvFilePath)) {
            return [
                Attachment::fromPath($this->csvFilePath),
            ];
        } else {
            return [];
        }
    }
}
