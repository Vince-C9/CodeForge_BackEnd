<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Contact Form Request Validation
 *
 * Handles validation for the main contact form submissions.
 * Applies strict validation rules and sanitization for security.
 */
class ContactFormRequest extends FormRequest
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
            'contact_reason' => ['required', Rule::in(['general', 'quote'])],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\.\']+$/', // Only letters, spaces, hyphens, dots, apostrophes
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
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
            'contact_reason.required' => 'Please select a contact reason.',
            'contact_reason.in' => 'Invalid contact reason selected.',
            'name.required' => 'Please provide your name.',
            'name.min' => 'Name must be at least 2 characters long.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, dots, and apostrophes.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'email.regex' => 'Email format is invalid.',
            'message.required' => 'Please provide a message.',
            'message.min' => 'Message must be at least 10 characters long.',
            'message.max' => 'Message cannot exceed 5000 characters.',
            'recaptcha_token.required' => 'Please complete the reCAPTCHA verification.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'contact_reason' => 'contact reason',
            'recaptcha_token' => 'reCAPTCHA',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * Sanitizes input data before validation to prevent XSS attacks.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitizeInput($this->input('name')),
            'email' => strtolower(trim($this->input('email'))),
            'message' => $this->sanitizeInput($this->input('message')),
        ]);
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

        // Remove any HTML tags
        $sanitized = strip_tags($input);

        // Remove any potentially harmful characters
        $sanitized = preg_replace('/[<>]/', '', $sanitized);

        // Trim whitespace
        return trim($sanitized);
    }
}
