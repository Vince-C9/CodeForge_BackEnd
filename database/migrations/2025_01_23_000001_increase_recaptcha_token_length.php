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
        Schema::table('form_submissions', function (Blueprint $table) {
            // Change recaptcha_token from VARCHAR(255) to TEXT to accommodate long tokens
            $table->text('recaptcha_token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            // Revert back to string (though this may cause data loss if tokens are long)
            $table->string('recaptcha_token')->nullable()->change();
        });
    }
};
