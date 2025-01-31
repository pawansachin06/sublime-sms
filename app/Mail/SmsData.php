<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Sms;

class SmsData extends Mailable
{
    use Queueable, SerializesModels;

    public Sms $sms;

    /**
     * Create a new message instance.
     */
    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
        $this->to([
            'simon@sublimex.com.au',
        ]);
        $this->bcc([
            'info@byvex.com',
            'pawansachin06@gmail.com',
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sms Delivery Callback',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sms-data',
            with: [
                'sms' => $this->sms,
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
