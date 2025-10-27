<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['contact', 'quote'])->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();

            // Contact form specific fields
            $table->enum('contact_reason', ['general', 'quote'])->nullable();
            $table->string('service')->nullable();

            // Quote form specific fields
            $table->json('configuration')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            // Security and tracking
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('recaptcha_token')->nullable();
            $table->decimal('recaptcha_score', 2, 1)->nullable();

            // Status tracking
            $table->enum('status', ['new', 'read', 'responded', 'archived'])->default('new')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('created_at');
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
