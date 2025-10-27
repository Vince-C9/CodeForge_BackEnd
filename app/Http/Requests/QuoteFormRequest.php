<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Quote Form Request Validation
 *
 * Handles validation for the website configurator quote requests.
 * Validates both contact details and configuration data.
 */
class QuoteFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Contact details
            'contactDetails.name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\.\']+$/',
            ],
            'contactDetails.email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'contactDetails.phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\d\s\+\-\(\)]+$/', // Allow digits, spaces, +, -, (, )
            ],
            'contactDetails.message' => [
                'nullable',
                'string',
                'max:2000',
            ],

            // Configuration details
            'configuration' => ['required', 'array'],
            'configuration.pageType' => ['required', Rule::in(['single', 'multi'])],
            'configuration.colors' => ['required', 'array'],
            'configuration.colors.primary' => [
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', // Valid hex color
            ],
            'configuration.colors.secondary' => [
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'configuration.sections' => ['required', 'array', 'min:1'],
            'configuration.sections.*' => ['required', 'string', 'max:50'],
            'configuration.additionalPages' => ['nullable', 'array'],
            'configuration.additionalPages.*.id' => ['required_with:configuration.additionalPages.*', 'string', 'max:50'],
            'configuration.additionalPages.*.name' => ['required_with:configuration.additionalPages.*', 'string', 'max:100'],
            'configuration.additionalPages.*.sections' => ['nullable', 'array'],
            'configuration.additionalPages.*.sections.*' => ['string', 'max:50'],
            'configuration.features' => ['nullable', 'array'],
            'configuration.features.*' => [
                'required',
                'string',
                Rule::in([
                    'booking-form',
                    'payment-gateway',
                    'blog',
                    'gallery',
                    'ecommerce-basic',
                    'contact-form-advanced'
                ]),
            ],

            // Logo file (if uploaded)
            'logo' => [
                'nullable',
                'file',
                'mimes:png,jpg,jpeg,svg,webp',
                'max:5120', // 5MB max
            ],

            // Total price
            'total' => [
                'required',
                'numeric',
                'min:300',
                'max:10000',
            ],

            // reCAPTCHA token (required for spam prevention)
            'recaptcha_token' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contactDetails.name.required' => 'Please provide your name.',
            'contactDetails.name.regex' => 'Name can only contain letters, spaces, hyphens, dots, and apostrophes.',
            'contactDetails.email.required' => 'Please provide your email address.',
            'contactDetails.email.email' => 'Please provide a valid email address.',
            'contactDetails.phone.regex' => 'Phone number format is invalid.',
            'configuration.required' => 'Configuration data is required.',
            'configuration.pageType.required' => 'Please select a page type.',
            'configuration.colors.primary.regex' => 'Primary color must be a valid hex color code.',
            'configuration.colors.secondary.regex' => 'Secondary color must be a valid hex color code.',
            'configuration.sections.min' => 'At least one section must be selected.',
            'configuration.features.*.in' => 'Invalid feature selected.',
            'logo.mimes' => 'Logo must be a PNG, JPG, JPEG, SVG, or WebP file.',
            'logo.max' => 'Logo file size cannot exceed 5MB.',
            'total.required' => 'Total price is required.',
            'total.min' => 'Total price must be at least £350.',
            'recaptcha_token.required' => 'reCAPTCHA verification is required.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * Sanitizes input data before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('contactDetails')) {
            $contactDetails = $this->input('contactDetails');

            $this->merge([
                'contactDetails' => [
                    'name' => $this->sanitizeInput($contactDetails['name'] ?? null),
                    'email' => strtolower(trim($contactDetails['email'] ?? '')),
                    'phone' => $this->sanitizeInput($contactDetails['phone'] ?? null),
                    'message' => $this->sanitizeInput($contactDetails['message'] ?? null),
                ],
            ]);
        }
    }

    /**
     * Sanitize user input to prevent XSS attacks
     *
     * @param string|null $input
     * @return string|null
     */
    private function sanitizeInput(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        $sanitized = strip_tags($input);
        $sanitized = preg_replace('/[<>]/', '', $sanitized);
        return trim($sanitized);
    }
}
