<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Quote Form Request Validation Tests
 *
 * Tests validation rules for the website configurator quote requests.
 * Focuses on additional pages validation to ensure proper handling of nested arrays.
 */
class QuoteFormRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Base valid quote request data
     *
     * @return array
     */
    private function getValidQuoteData(): array
    {
        return [
            'contactDetails' => [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'phone' => '+44 7123 456789',
                'message' => 'Test message',
            ],
            'configuration' => [
                'pageType' => 'multi',
                'colors' => [
                    'primary' => '#3B82F6',
                    'secondary' => '#1E40AF',
                ],
                'sections' => ['hero', 'about', 'contact'],
                'additionalPages' => [],
                'features' => [],
            ],
            'total' => 300,
            'recaptcha_token' => 'test-token',
        ];
    }

    /**
     * Test quote submission without additional pages succeeds
     *
     * @return void
     */
    public function test_quote_submission_without_additional_pages_succeeds(): void
    {
        $data = $this->getValidQuoteData();

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test quote submission with valid additional pages succeeds
     *
     * @return void
     */
    public function test_quote_submission_with_valid_additional_pages_succeeds(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'name' => 'Services',
                'sections' => ['hero', 'services-list', 'cta'],
            ],
            [
                'id' => 'portfolio',
                'name' => 'Portfolio',
                'sections' => ['gallery', 'testimonials'],
            ],
        ];
        $data['total'] = 400; // Base 300 + 2 pages * 50

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test quote submission with single additional page succeeds
     *
     * @return void
     */
    public function test_quote_submission_with_single_additional_page_succeeds(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'about-us',
                'name' => 'About Us',
                'sections' => ['team', 'history'],
            ],
        ];
        $data['total'] = 350; // Base 300 + 1 page * 50

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test validation fails when additional page is missing id
     *
     * @return void
     */
    public function test_validation_fails_when_additional_page_missing_id(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'name' => 'Services',
                'sections' => ['hero', 'services-list'],
            ],
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.additionalPages.0.id']);
    }

    /**
     * Test validation fails when additional page is missing name
     *
     * @return void
     */
    public function test_validation_fails_when_additional_page_missing_name(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'sections' => ['hero', 'services-list'],
            ],
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.additionalPages.0.name']);
    }

    /**
     * Test validation fails when additional page is missing sections
     *
     * @return void
     */
    public function test_validation_fails_when_additional_page_missing_sections(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'name' => 'Services',
            ],
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.additionalPages.0.sections']);
    }

    /**
     * Test validation fails when additional page has empty sections array
     *
     * @return void
     */
    public function test_validation_fails_when_additional_page_has_empty_sections(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'name' => 'Services',
                'sections' => [],
            ],
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.additionalPages.0.sections']);
    }

    /**
     * Test validation passes for valid section names within additional pages
     *
     * @return void
     */
    public function test_validation_passes_for_valid_section_names(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'name' => 'Services',
                'sections' => ['hero', 'about', 'services-list', 'pricing', 'contact'],
            ],
        ];
        $data['total'] = 350;

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(201);
    }

    /**
     * Test validation fails when section name exceeds max length
     *
     * @return void
     */
    public function test_validation_fails_when_section_name_exceeds_max_length(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'name' => 'Services',
                'sections' => [str_repeat('a', 51)], // 51 characters (max is 50)
            ],
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.additionalPages.0.sections.0']);
    }

    /**
     * Test validation with multiple pages and mixed valid/invalid data
     *
     * @return void
     */
    public function test_validation_with_multiple_pages_detects_errors_in_second_page(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['additionalPages'] = [
            [
                'id' => 'services',
                'name' => 'Services',
                'sections' => ['hero', 'services-list'],
            ],
            [
                'id' => 'portfolio',
                'name' => 'Portfolio',
                'sections' => [], // Invalid: empty sections
            ],
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.additionalPages.1.sections']);
    }

    /**
     * Test validation requires contact details
     *
     * @return void
     */
    public function test_validation_requires_contact_details(): void
    {
        $data = $this->getValidQuoteData();
        unset($data['contactDetails']);

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'contactDetails.name',
                'contactDetails.email',
            ]);
    }

    /**
     * Test validation requires configuration
     *
     * @return void
     */
    public function test_validation_requires_configuration(): void
    {
        $data = $this->getValidQuoteData();
        unset($data['configuration']);

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration']);
    }

    /**
     * Test validation requires at least one default section
     *
     * @return void
     */
    public function test_validation_requires_at_least_one_default_section(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['sections'] = [];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.sections']);
    }

    /**
     * Test validation accepts valid hex colors
     *
     * @return void
     */
    public function test_validation_accepts_valid_hex_colors(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['colors'] = [
            'primary' => '#ABC',
            'secondary' => '#123456',
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(201);
    }

    /**
     * Test validation rejects invalid hex colors
     *
     * @return void
     */
    public function test_validation_rejects_invalid_hex_colors(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['colors'] = [
            'primary' => 'blue', // Invalid
            'secondary' => '#123456',
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.colors.primary']);
    }

    /**
     * Test validation enforces minimum and maximum total price
     *
     * @return void
     */
    public function test_validation_enforces_price_constraints(): void
    {
        $data = $this->getValidQuoteData();
        $data['total'] = 100; // Below minimum of 300

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['total']);

        $data['total'] = 15000; // Above maximum of 10000

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['total']);
    }

    /**
     * Test validation requires recaptcha token
     *
     * @return void
     */
    public function test_validation_requires_recaptcha_token(): void
    {
        $data = $this->getValidQuoteData();
        unset($data['recaptcha_token']);

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['recaptcha_token']);
    }

    /**
     * Test validation accepts valid features
     *
     * @return void
     */
    public function test_validation_accepts_valid_features(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['features'] = [
            'booking-form',
            'blog',
            'gallery',
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(201);
    }

    /**
     * Test validation rejects invalid features
     *
     * @return void
     */
    public function test_validation_rejects_invalid_features(): void
    {
        $data = $this->getValidQuoteData();
        $data['configuration']['features'] = [
            'invalid-feature',
        ];

        $response = $this->postJson('/api/quote', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['configuration.features.0']);
    }

    /**
     * Test validation accepts valid logo file upload
     *
     * @return void
     */
    public function test_validation_accepts_valid_logo_upload(): void
    {
        Storage::fake('local');

        $data = $this->getValidQuoteData();
        $logo = UploadedFile::fake()->image('logo.png', 200, 200)->size(1024); // 1MB

        $response = $this->post('/api/quote', array_merge($data, ['logo' => $logo]));

        $response->assertStatus(201);
    }

    /**
     * Test validation rejects logo files that are too large
     *
     * @return void
     */
    public function test_validation_rejects_oversized_logo(): void
    {
        Storage::fake('local');

        $data = $this->getValidQuoteData();
        $logo = UploadedFile::fake()->image('logo.png', 5000, 5000)->size(6144); // 6MB (over 5MB limit)

        $response = $this->post('/api/quote', array_merge($data, ['logo' => $logo]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['logo']);
    }

    /**
     * Test validation rejects invalid logo file types
     *
     * @return void
     */
    public function test_validation_rejects_invalid_logo_file_type(): void
    {
        Storage::fake('local');

        $data = $this->getValidQuoteData();
        $logo = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->post('/api/quote', array_merge($data, ['logo' => $logo]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['logo']);
    }
}
