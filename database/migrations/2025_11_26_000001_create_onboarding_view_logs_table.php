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
        Schema::create('onboarding_view_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->nullable()->index();
            $table->unsignedBigInteger('actor_user_id')->nullable()->index();
            $table->string('actor_name')->nullable();
            $table->string('actor_role')->nullable();
            $table->string('tenant_slug')->index();
            $table->enum('tenant_source', ['subdomain', 'slug'])->default('slug');
            $table->string('event_type', 100)->index(); // Page.View, Onboarding.SlideOver.Open, etc.
            $table->string('resource_type', 50)->nullable(); // onboarding, candidate, tab, page
            $table->uuid('resource_id')->nullable()->index();
            $table->text('url');
            $table->string('user_agent', 500)->nullable();
            $table->string('ip_address', 45)->nullable()->index();
            $table->json('extra')->nullable();
            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->nullable();

            // Indexes for common queries
            $table->index(['tenant_id', 'event_type', 'created_at']);
            $table->index(['tenant_slug', 'created_at']);
            $table->index(['actor_user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_view_logs');
    }
};

