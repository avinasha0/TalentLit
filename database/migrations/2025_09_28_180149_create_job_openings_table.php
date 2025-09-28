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
        Schema::create('job_openings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('requisition_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->uuid('department_id');
            $table->uuid('location_id');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time');
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->integer('openings_count')->default(1);
            $table->longText('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'department_id']);
            $table->index(['tenant_id', 'location_id']);
            $table->unique(['tenant_id', 'slug']);
            $table->foreign('requisition_id')->references('id')->on('job_requisitions')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_openings');
    }
};
