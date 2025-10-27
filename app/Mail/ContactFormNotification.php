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
 * Contact Form Notification Email
 *
 * Sends notification when a contact form is submitted.
 * Uses Laravel's Mailable class for clean email composition.
 */
class ContactFormNotification extends Mailable
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
        // Determine recipient based on contact reason
        $recipientAddress = $this->submission->contact_reason === 'quote'
            ? config('mail.quotes_address', config('mail.from.address'))
            : config('mail.info_address', config('mail.from.address'));

        $subject = $this->submission->contact_reason === 'quote'
            ? 'New Quote Inquiry from Website'
            : 'New Contact Form Submission';

        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            to: [
                new Address($recipientAddress, config('mail.from.name'))
            ],
            replyTo: [
                new Address($this->submission->email, $this->submission->name)
            ],
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.contact-form',
            text: 'emails.contact-form-text',
            with: [
                'submission' => $this->submission,
                'contactReason' => $this->getContactReasonLabel(),
                'service' => $this->getServiceLabel(),
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

    /**
     * Get contact reason label
     *
     * @return string
     */
    private function getContactReasonLabel(): string
    {
        return match ($this->submission->contact_reason) {
            'quote' => 'Request a Quote',
            'general' => 'General Inquiry',
            default => 'Not Specified',
        };
    }

    /**
     * Get service label
     *
     * @return string|null
     */
    private function getServiceLabel(): ?string
    {
        if (!$this->submission->service) {
            return null;
        }

        return match ($this->submission->service) {
            'contract' => 'Contract Work',
            'website' => '£350 Website Package',
            'custom' => 'Custom Project',
            default => $this->submission->service,
        };
    }
}
