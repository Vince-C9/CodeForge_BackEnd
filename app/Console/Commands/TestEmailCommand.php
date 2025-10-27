<?php

namespace App\Console\Commands;

use App\Mail\ContactFormNotification;
use App\Mail\QuoteRequestNotification;
use App\Models\FormSubmission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {type=contact : Type of email to test (contact or quote)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to Mailpit to verify email configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type');

        $this->info("Testing email delivery to Mailpit...");
        $this->newLine();

        try {
            if ($type === 'quote') {
                $this->sendQuoteTestEmail();
            } else {
                $this->sendContactTestEmail();
            }

            $this->newLine();
            $this->info('Email sent successfully!');
            $this->info('Check Mailpit at: http://localhost:8025');
            $this->newLine();
            $this->line('Configuration used:');
            $this->line('  MAIL_MAILER: ' . config('mail.default'));
            $this->line('  MAIL_HOST: ' . config('mail.mailers.smtp.host'));
            $this->line('  MAIL_PORT: ' . config('mail.mailers.smtp.port'));
            $this->line('  MAIL_FROM_ADDRESS: ' . config('mail.from.address'));

            if ($type === 'quote') {
                $this->line('  MAIL_QUOTES_ADDRESS: ' . config('mail.quotes_address'));
            } else {
                $this->line('  MAIL_INFO_ADDRESS: ' . config('mail.info_address'));
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Send a test contact form email
     */
    private function sendContactTestEmail(): void
    {
        $this->info('Sending test Contact Form email...');

        $submission = new FormSubmission([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => '01234 567890',
            'contact_reason' => 'general',
            'service' => 'custom',
            'message' => 'This is a test email from the Laravel artisan command to verify Mailpit configuration.',
            'recaptcha_score' => 0.9,
            'ip_address' => '127.0.0.1',
        ]);

        // Set properties that aren't mass assignable
        $submission->id = 99999;
        $submission->created_at = now();

        Mail::send(new ContactFormNotification($submission));

        $this->line('Recipient: ' . config('mail.info_address'));
        $this->line('Subject: New Contact Form Submission');
    }

    /**
     * Send a test quote request email
     */
    private function sendQuoteTestEmail(): void
    {
        $this->info('Sending test Quote Request email...');

        $submission = new FormSubmission([
            'name' => 'Test Client',
            'email' => 'testclient@example.com',
            'phone' => '01234 567890',
            'total_price' => 525.00,
            'recaptcha_score' => 0.9,
            'ip_address' => '127.0.0.1',
            'configuration' => [
                'pageType' => 'multi',
                'colors' => [
                    'primary' => '#3B82F6',
                    'secondary' => '#10B981',
                ],
                'sections' => [
                    'hero',
                    'services',
                    'about',
                    'contact',
                ],
                'additionalPages' => [
                    ['name' => 'About Us', 'description' => 'Company information'],
                    ['name' => 'Services', 'description' => 'Our services'],
                ],
                'features' => [
                    'booking-form',
                    'gallery',
                ],
            ],
        ]);

        // Set properties that aren't mass assignable
        $submission->id = 88888;
        $submission->created_at = now();

        Mail::send(new QuoteRequestNotification($submission));

        $this->line('Recipient: ' . config('mail.quotes_address'));
        $this->line('Subject: New Website Quote Request - £525.00');
    }
}
