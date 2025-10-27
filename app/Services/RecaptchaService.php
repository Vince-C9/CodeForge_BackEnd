<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ReCAPTCHA Service
 *
 * Handles Google reCAPTCHA v3 verification.
 * Provides a single responsibility for validating reCAPTCHA tokens.
 */
class RecaptchaService
{
    /**
     * Minimum acceptable reCAPTCHA score (0.0 to 1.0)
     * Scores below this threshold are considered bots
     */
    private const MINIMUM_SCORE = 0.5;

    /**
     * Google reCAPTCHA API endpoint
     */
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Verify reCAPTCHA token with Google
     *
     * @param string $token The reCAPTCHA token from the frontend
     * @param string|null $remoteIp Optional IP address of the user
     * @return array{success: bool, score: float|null, errors: array}
     */
    public function verify(string $token, ?string $remoteIp = null): array
    {
        // Skip verification if explicitly configured to skip testing
        // This allows for local development without actual reCAPTCHA verification
        if (config('services.recaptcha.skip_in_testing', false)) {
            Log::info('reCAPTCHA verification skipped (testing mode enabled)');

            return [
                'success' => true,
                'score' => 1.0,
                'errors' => [],
            ];
        }

        $secretKey = config('services.recaptcha.secret_key');

        // If no secret key is configured, log warning and return failure
        if (empty($secretKey)) {
            Log::warning('reCAPTCHA secret key not configured');

            return [
                'success' => false,
                'score' => null,
                'errors' => ['reCAPTCHA is not properly configured'],
            ];
        }

        try {
            $response = Http::asForm()->post(self::VERIFY_URL, [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => $remoteIp,
            ]);

            if (!$response->successful()) {
                Log::error('reCAPTCHA API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'score' => null,
                    'errors' => ['Failed to verify reCAPTCHA'],
                ];
            }

            $data = $response->json();

            // Check if verification was successful
            if (!($data['success'] ?? false)) {
                Log::warning('reCAPTCHA verification failed', [
                    'errors' => $data['error-codes'] ?? [],
                ]);

                return [
                    'success' => false,
                    'score' => null,
                    'errors' => $this->parseErrorCodes($data['error-codes'] ?? []),
                ];
            }

            $score = $data['score'] ?? 0.0;

            // Check if score meets minimum threshold
            if ($score < self::MINIMUM_SCORE) {
                Log::info('reCAPTCHA score below threshold', [
                    'score' => $score,
                    'threshold' => self::MINIMUM_SCORE,
                ]);

                return [
                    'success' => false,
                    'score' => $score,
                    'errors' => ['Verification score too low. Please try again.'],
                ];
            }

            return [
                'success' => true,
                'score' => $score,
                'errors' => [],
            ];

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'score' => null,
                'errors' => ['An error occurred during verification'],
            ];
        }
    }

    /**
     * Parse reCAPTCHA error codes into user-friendly messages
     *
     * @param array $errorCodes
     * @return array
     */
    private function parseErrorCodes(array $errorCodes): array
    {
        $messages = [];

        foreach ($errorCodes as $code) {
            $messages[] = match ($code) {
                'missing-input-secret' => 'The secret parameter is missing',
                'invalid-input-secret' => 'The secret parameter is invalid or malformed',
                'missing-input-response' => 'The response parameter is missing',
                'invalid-input-response' => 'The response parameter is invalid or malformed',
                'bad-request' => 'The request is invalid or malformed',
                'timeout-or-duplicate' => 'The response is no longer valid: either is too old or has been used previously',
                default => 'reCAPTCHA verification failed',
            };
        }

        return $messages;
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return !empty(config('services.recaptcha.secret_key'));
    }
}
