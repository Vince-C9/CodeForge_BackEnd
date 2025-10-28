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
 * Quote Request Notification Email
 *
 * Sends notification when a quote is requested from the configurator.
 * Uses Laravel's Mailable class for clean email composition.
 */
class QuoteRequestNotification extends Mailable
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
                new Address(
                    config('mail.quotes_address', config('mail.from.address')),
                    config('mail.from.name')
                )
            ],
            replyTo: [
                new Address($this->submission->email, $this->submission->name)
            ],
            subject: sprintf(
                'New Website Quote Request - £%s',
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
            html: 'emails.quote-request',
            text: 'emails.quote-request-text',
            with: [
                'submission' => $this->submission,
                'configuration' => $this->submission->configuration,
                'pageType' => $this->getPageTypeLabel(),
                'features' => $this->getFormattedFeatures(),
                'basePrice' => 350,
                'additionalPagesCount' => count($this->submission->configuration['additionalPages'] ?? []),
                'additionalPagesPrice' => count($this->submission->configuration['additionalPages'] ?? []) * 50,
                'featuresTotal' => $this->calculateFeaturesTotal(),
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
     * Get page type label
     *
     * @return string
     */
    private function getPageTypeLabel(): string
    {
        $pageType = $this->submission->configuration['pageType'] ?? 'single';

        return match ($pageType) {
            'single' => 'Single Page Website',
            'multi' => 'Multi-Page Website',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted features list
     *
     * @return array
     */
    private function getFormattedFeatures(): array
    {
        $features = $this->submission->configuration['features'] ?? [];
        $formatted = [];

        $featureNames = [
            'booking-form' => 'Booking System',
            'payment-gateway' => 'Payment Gateway',
            'blog' => 'Blog/News',
            'gallery' => 'Image Gallery',
            'ecommerce-basic' => 'Basic E-commerce',
            'contact-form-advanced' => 'Advanced Contact Form',
        ];

        $featurePrices = [
            'booking-form' => 100,
            'payment-gateway' => 150,
            'blog' => 75,
            'gallery' => 50,
            'ecommerce-basic' => 200,
            'contact-form-advanced' => 50,
        ];

        foreach ($features as $feature) {
            $formatted[] = [
                'name' => $featureNames[$feature] ?? $feature,
                'price' => $featurePrices[$feature] ?? 0,
            ];
        }

        return $formatted;
    }

    /**
     * Calculate total price for features
     *
     * @return float
     */
    private function calculateFeaturesTotal(): float
    {
        $features = $this->submission->configuration['features'] ?? [];
        $total = 0;

        $featurePrices = [
            'booking-form' => 100,
            'payment-gateway' => 150,
            'blog' => 75,
            'gallery' => 50,
            'ecommerce-basic' => 200,
            'contact-form-advanced' => 50,
        ];

        foreach ($features as $feature) {
            $total += $featurePrices[$feature] ?? 0;
        }

        return $total;
    }
}
