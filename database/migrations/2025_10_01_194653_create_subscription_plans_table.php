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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Pro, Enterprise
            $table->string('slug')->unique(); // free, pro, enterprise
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0); // Monthly price
            $table->string('currency', 3)->default('USD');
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            
            // Feature limits
            $table->integer('max_users')->default(1);
            $table->integer('max_job_openings')->default(5);
            $table->integer('max_candidates')->default(100);
            $table->integer('max_applications_per_month')->default(50);
            $table->integer('max_interviews_per_month')->default(20);
            $table->integer('max_storage_gb')->default(1);
            $table->boolean('analytics_enabled')->default(false);
            $table->boolean('custom_branding')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('advanced_reporting')->default(false);
            $table->boolean('integrations')->default(false);
            $table->boolean('white_label')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
