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
        Schema::create('application_stage_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('application_id');
            $table->uuid('from_stage_id')->nullable();
            $table->uuid('to_stage_id');
            $table->uuid('moved_by_user_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at');

            $table->index(['tenant_id', 'application_id']);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('from_stage_id')->references('id')->on('job_stages')->onDelete('set null');
            $table->foreign('to_stage_id')->references('id')->on('job_stages')->onDelete('cascade');
            // Note: users table uses auto-incrementing IDs, not UUIDs
            // $table->foreign('moved_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_stage_events');
    }
};
