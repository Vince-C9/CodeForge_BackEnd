<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteFormRequest;
use App\Services\Contracts\FormSubmissionServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Quote Request Controller
 *
 * Handles quote request submissions from the website configurator.
 * Follows Single Responsibility Principle - only handles HTTP concerns.
 * Delegates business logic to services (Dependency Inversion Principle).
 */
class QuoteRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param FormSubmissionServiceInterface $formSubmissionService
     */
    public function __construct(
        private readonly FormSubmissionServiceInterface $formSubmissionService
    ) {}

    /**
     * Handle quote request submission from the configurator
     *
     * @param QuoteFormRequest $request
     * @return JsonResponse
     */
    public function submit(QuoteFormRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Add logo file if present
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo');
            }

            // Process the quote request
            $submission = $this->formSubmissionService->processQuoteRequest(
                $data,
                $request->ip(),
                $request->userAgent()
            );

            Log::info('Quote request submitted successfully', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
                'total_price' => $submission->total_price,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your quote request! We will review your requirements and send you a detailed quote within 48 hours.',
                'submission_id' => $submission->id,
                'estimated_total' => $submission->total_price,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Quote request submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your quote request. Please try again later.',
            ], 500);
        }
    }
}
