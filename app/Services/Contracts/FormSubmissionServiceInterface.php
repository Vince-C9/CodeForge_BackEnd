<?php

namespace App\Services\Contracts;

use App\Models\FormSubmission;

/**
 * Form Submission Service Interface
 *
 * Defines the contract for form submission processing services.
 * Follows the Interface Segregation Principle - clients only depend on methods they use.
 */
interface FormSubmissionServiceInterface
{
    /**
     * Process a contact form submission
     *
     * @param array $data Validated form data
     * @param string|null $ipAddress User's IP address
     * @param string|null $userAgent User's user agent string
     * @return FormSubmission
     */
    public function processContactForm(array $data, ?string $ipAddress = null, ?string $userAgent = null): FormSubmission;

    /**
     * Process a quote request submission
     *
     * @param array $data Validated form data
     * @param string|null $ipAddress User's IP address
     * @param string|null $userAgent User's user agent string
     * @return FormSubmission
     */
    public function processQuoteRequest(array $data, ?string $ipAddress = null, ?string $userAgent = null): FormSubmission;
}
