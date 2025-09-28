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
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('job_opening_id');
            $table->uuid('candidate_id');
            $table->enum('status', ['active', 'withdrawn', 'hired', 'rejected'])->default('active');
            $table->uuid('current_stage_id')->nullable();
            $table->timestamp('applied_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'job_opening_id']);
            $table->index(['tenant_id', 'candidate_id']);
            $table->index(['tenant_id', 'status']);
            $table->unique(['tenant_id', 'job_opening_id', 'candidate_id']);
            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('current_stage_id')->references('id')->on('job_stages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
