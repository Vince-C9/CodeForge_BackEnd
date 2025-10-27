<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Home Page Contact Form Request Validation
 *
 * Handles validation for the contact form on the home page.
 * This form has slightly different fields than the main contact form.
 */
class HomeContactFormRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\.\']+$/',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'service' => [
                'required',
                Rule::in(['contract', 'website', 'custom']),
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
            'name.required' => 'Please provide your name.',
            'name.min' => 'Name must be at least 2 characters long.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, dots, and apostrophes.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'service.required' => 'Please select a service.',
            'service.in' => 'Invalid service selected.',
            'message.required' => 'Please provide a message.',
            'message.min' => 'Message must be at least 10 characters long.',
            'message.max' => 'Message cannot exceed 5000 characters.',
            'recaptcha_token.required' => 'Please complete the reCAPTCHA verification.',
        ];
    }

    /**
     * Prepare the data for validation.
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

        $sanitized = strip_tags($input);
        $sanitized = preg_replace('/[<>]/', '', $sanitized);
        return trim($sanitized);
    }
}
