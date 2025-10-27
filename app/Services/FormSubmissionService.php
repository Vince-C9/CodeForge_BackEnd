<?php

namespace App\Services;

use App\Mail\ContactFormNotification;
use App\Mail\QuoteRequestNotification;
use App\Models\FormSubmission;
use App\Services\Contracts\FormSubmissionServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Form Submission Service
 *
 * Handles the business logic for processing form submissions.
 * Follows Single Responsibility Principle - only handles form submission processing.
 * Depends on abstractions (Mail facade) rather than concrete implementations (DIP).
 */
class FormSubmissionService implements FormSubmissionServiceInterface
{
    /**
     * Process a contact form submission
     *
     * @param array $data Validated form data
     * @param string|null $ipAddress User's IP address
     * @param string|null $userAgent User's user agent string
     * @return FormSubmission
     * @throws \Exception
     */
    public function processContactForm(array $data, ?string $ipAddress = null, ?string $userAgent = null): FormSubmission
    {
        return DB::transaction(function () use ($data, $ipAddress, $userAgent) {
            // Create form submission record
            $submission = FormSubmission::create([
                'type' => 'contact',
                'name' => $data['name'],
                'email' => $data['email'],
                'message' => $data['message'],
                'contact_reason' => $data['contact_reason'] ?? null,
                'service' => $data['service'] ?? null,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'recaptcha_token' => $data['recaptcha_token'] ?? null,
                'recaptcha_score' => $data['recaptcha_score'] ?? null,
                'status' => 'new',
            ]);

            // Send email notification
            try {
                Mail::send(new ContactFormNotification($submission));
            } catch (\Exception $e) {
                // Log email error but don't fail the submission
                Log::error('Failed to send contact form notification email', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $submission;
        });
    }

    /**
     * Process a quote request submission
     *
     * @param array $data Validated form data
     * @param string|null $ipAddress User's IP address
     * @param string|null $userAgent User's user agent string
     * @return FormSubmission
     * @throws \Exception
     */
    public function processQuoteRequest(array $data, ?string $ipAddress = null, ?string $userAgent = null): FormSubmission
    {
        return DB::transaction(function () use ($data, $ipAddress, $userAgent) {
            $contactDetails = $data['contactDetails'];
            $configuration = $data['configuration'];

            // Handle logo file upload if present
            $logoPath = null;
            if (isset($data['logo']) && $data['logo'] !== null) {
                $logoPath = $this->storeLogo($data['logo']);
                $configuration['logo_path'] = $logoPath;
            }

            // Create form submission record
            $submission = FormSubmission::create([
                'type' => 'quote',
                'name' => $contactDetails['name'],
                'email' => $contactDetails['email'],
                'phone' => $contactDetails['phone'] ?? null,
                'message' => $contactDetails['message'] ?? null,
                'configuration' => $configuration,
                'total_price' => $data['total'],
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'recaptcha_score' => $data['recaptcha_score'] ?? null,
                'status' => 'new',
            ]);

            // Send email notification
            try {
                Mail::send(new QuoteRequestNotification($submission));
            } catch (\Exception $e) {
                // Log email error but don't fail the submission
                Log::error('Failed to send quote request notification email', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $submission;
        });
    }

    /**
     * Store uploaded logo file
     *
     * @param \Illuminate\Http\UploadedFile $logo
     * @return string The stored file path
     */
    private function storeLogo($logo): string
    {
        $filename = sprintf(
            '%s_%s.%s',
            'logo',
            uniqid(),
            $logo->getClientOriginalExtension()
        );

        $path = $logo->storeAs('logos', $filename, 'public');

        Log::info('Logo uploaded', [
            'original_name' => $logo->getClientOriginalName(),
            'stored_path' => $path,
        ]);

        return $path;
    }

    /**
     * Delete a form submission and associated files
     *
     * @param FormSubmission $submission
     * @return bool
     */
    public function deleteSubmission(FormSubmission $submission): bool
    {
        // Delete logo file if exists
        if ($submission->isQuoteRequest() && isset($submission->configuration['logo_path'])) {
            Storage::disk('public')->delete($submission->configuration['logo_path']);
        }

        return $submission->delete();
    }

    /**
     * Mark submission as read
     *
     * @param FormSubmission $submission
     * @return bool
     */
    public function markAsRead(FormSubmission $submission): bool
    {
        return $submission->markAsRead();
    }

    /**
     * Mark submission as responded
     *
     * @param FormSubmission $submission
     * @return bool
     */
    public function markAsResponded(FormSubmission $submission): bool
    {
        return $submission->markAsResponded();
    }
}
