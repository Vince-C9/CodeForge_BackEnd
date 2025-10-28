<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Quote Request Confirmation Email
 *
 * Sends confirmation to the customer after they request a quote.
 * Provides important information about domain renewals and hosting.
 */
class QuoteRequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param FormSubmission $submission
     */
    public function __construct(
        public FormSubmission $submission
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            to: [
                new Address($this->submission->email, $this->submission->name)
            ],
            subject: sprintf(
                'Quote Request Received - Estimated £%s - CodeForge Systems',
                number_format($this->submission->total_price, 2)
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.quote-request-confirmation',
            with: [
                'submission' => $this->submission,
                'configuration' => $this->submission->configuration,
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
