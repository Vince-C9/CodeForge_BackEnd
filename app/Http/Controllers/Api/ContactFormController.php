<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\HomeContactFormRequest;
use App\Services\Contracts\FormSubmissionServiceInterface;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Contact Form Controller
 *
 * Handles contact form submissions from the website.
 * Follows Single Responsibility Principle - only handles HTTP concerns.
 * Delegates business logic to services (Dependency Inversion Principle).
 */
class ContactFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param FormSubmissionServiceInterface $formSubmissionService
     * @param RecaptchaService $recaptchaService
     */
    public function __construct(
        private readonly FormSubmissionServiceInterface $formSubmissionService,
        private readonly RecaptchaService $recaptchaService
    ) {}

    /**
     * Handle contact form submission from the contact page
     *
     * @param ContactFormRequest $request
     * @return JsonResponse
     */
    public function submitContactForm(ContactFormRequest $request): JsonResponse
    {
        try {
            // Verify reCAPTCHA
            $recaptchaResult = $this->recaptchaService->verify(
                $request->validated()['recaptcha_token'],
                $request->ip()
            );

            if (!$recaptchaResult['success']) {
                Log::warning('Contact form submission failed reCAPTCHA verification', [
                    'email' => $request->validated()['email'],
                    'errors' => $recaptchaResult['errors'],
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Security verification failed. Please try again.',
                    'errors' => $recaptchaResult['errors'],
                ], 422);
            }

            // Add reCAPTCHA score to the data
            $data = $request->validated();
            $data['recaptcha_score'] = $recaptchaResult['score'];

            // Process the form submission
            $submission = $this->formSubmissionService->processContactForm(
                $data,
                $request->ip(),
                $request->userAgent()
            );

            Log::info('Contact form submitted successfully', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you within 24-48 hours.',
                'submission_id' => $submission->id,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your message. Please try again later.',
            ], 500);
        }
    }

    /**
     * Handle contact form submission from the home page
     *
     * @param HomeContactFormRequest $request
     * @return JsonResponse
     */
    public function submitHomeContactForm(HomeContactFormRequest $request): JsonResponse
    {
        try {
            // Verify reCAPTCHA
            $recaptchaResult = $this->recaptchaService->verify(
                $request->validated()['recaptcha_token'],
                $request->ip()
            );

            if (!$recaptchaResult['success']) {
                Log::warning('Home contact form submission failed reCAPTCHA verification', [
                    'email' => $request->validated()['email'],
                    'errors' => $recaptchaResult['errors'],
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Security verification failed. Please try again.',
                    'errors' => $recaptchaResult['errors'],
                ], 422);
            }

            // Add reCAPTCHA score to the data
            $data = $request->validated();
            $data['recaptcha_score'] = $recaptchaResult['score'];

            // Process the form submission
            $submission = $this->formSubmissionService->processContactForm(
                $data,
                $request->ip(),
                $request->userAgent()
            );

            Log::info('Home contact form submitted successfully', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you within 24-48 hours.',
                'submission_id' => $submission->id,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Home contact form submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your message. Please try again later.',
            ], 500);
        }
    }
}
