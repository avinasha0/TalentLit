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
        Schema::create('newsletter_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('source')->nullable(); // website, landing_page, etc.
            $table->json('metadata')->nullable(); // additional data like IP, user agent, etc.
            $table->timestamps();
            
            $table->index(['email', 'otp']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_otps');
    }
};
