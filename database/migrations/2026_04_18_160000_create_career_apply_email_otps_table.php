<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_apply_email_otps', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->uuid('job_opening_id');
            $table->string('email');
            $table->string('otp', 6);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'job_opening_id', 'email']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_apply_email_otps');
    }
};
