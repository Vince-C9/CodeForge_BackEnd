<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContactFormController;
use App\Http\Controllers\Api\QuoteRequestController;
use App\Http\Controllers\BetaAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Form Submission Routes
|--------------------------------------------------------------------------
|
| These routes handle form submissions from the Nuxt frontend.
| - Rate limiting is applied to prevent abuse
| - CORS is configured in config/cors.php
| - All inputs are validated through Form Requests
|
*/

// Contact Form Submissions
Route::prefix('contact')->group(function () {
    // Main contact page form (with reCAPTCHA)
    Route::post('/', [ContactFormController::class, 'submitContactForm'])
        ->middleware('throttle:contact-forms')
        ->name('api.contact.submit');

    // Home page contact form (without reCAPTCHA)
    Route::post('/home', [ContactFormController::class, 'submitHomeContactForm'])
        ->middleware('throttle:contact-forms')
        ->name('api.contact.home.submit');
});

// Quote Request Submissions
Route::prefix('quote')->group(function () {
    // Website configurator quote request
    Route::post('/', [QuoteRequestController::class, 'submit'])
        ->middleware('throttle:quote-requests')
        ->name('api.quote.submit');
});

// Beta Authentication Routes
Route::prefix('beta')->group(function () {
    // Login to beta access
    Route::post('/login', [BetaAuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('api.beta.login');

    // Logout from beta access
    Route::post('/logout', [BetaAuthController::class, 'logout'])
        ->name('api.beta.logout');

    // Check beta access status
    Route::get('/check-access', [BetaAuthController::class, 'checkAccess'])
        ->name('api.beta.check-access');
});

// Article Routes
Route::prefix('articles')->group(function () {
    // Get paginated list of articles
    Route::get('/', [ArticleController::class, 'index'])
        ->name('api.articles.index');

    // Get single article by slug
    Route::get('/{slug}', [ArticleController::class, 'show'])
        ->name('api.articles.show');
});
