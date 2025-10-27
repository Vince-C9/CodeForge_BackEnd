<?php

namespace App\Providers;

use App\Services\Contracts\FormSubmissionServiceInterface;
use App\Services\FormSubmissionService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

/**
 * Application Service Provider
 *
 * Registers service bindings and bootstraps the application.
 * Follows Dependency Inversion Principle by binding interfaces to implementations.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        FormSubmissionServiceInterface::class => FormSubmissionService::class,
    ];

    /**
     * Register any application services.
     *
     * This method is called during the registration phase of the service container.
     * Only bind services here - do NOT use facades or resolve dependencies.
     */
    public function register(): void
    {
        // Additional service registrations can be added here
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all service providers have been registered.
     * It's safe to use facades and resolve dependencies here.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure rate limiting for the application.
     *
     * Rate limiters are defined here (in boot method) to ensure the application
     * is fully bootstrapped and facades are available.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        // Contact form rate limiting: 3 submissions per minute per IP
        RateLimiter::for('contact-forms', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many submissions. Please try again in a few minutes.',
                    ], 429, $headers);
                });
        });

        // Quote request rate limiting: 2 submissions per minute per IP
        RateLimiter::for('quote-requests', function (Request $request) {
            return Limit::perMinute(2)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many quote requests. Please try again in a few minutes.',
                    ], 429, $headers);
                });
        });
    }
}
