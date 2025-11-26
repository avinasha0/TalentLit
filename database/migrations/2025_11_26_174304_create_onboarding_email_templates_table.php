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
        Schema::create('onboarding_email_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('template_key')->unique();
            $table->string('name');
            $table->text('purpose')->nullable();
            $table->string('subject');
            $table->text('body');
            $table->uuid('tenant_id')->nullable(); // null = global template
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'template_key']);
            $table->index('template_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_email_templates');
    }
};
