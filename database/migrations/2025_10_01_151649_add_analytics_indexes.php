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
        // Add indexes for analytics performance
        Schema::table('applications', function (Blueprint $table) {
            // Composite index for tenant + date range queries
            $table->index(['tenant_id', 'applied_at'], 'idx_applications_tenant_applied_at');
            // Index for hired applications with date range
            $table->index(['tenant_id', 'status', 'applied_at'], 'idx_applications_tenant_status_applied_at');
            // Index for stage funnel queries
            $table->index(['tenant_id', 'current_stage_id', 'applied_at'], 'idx_applications_tenant_stage_applied_at');
            // Index for pipeline snapshot
            $table->index(['tenant_id', 'status', 'current_stage_id'], 'idx_applications_tenant_status_stage');
        });

        Schema::table('job_openings', function (Blueprint $table) {
            // Index for open jobs by department
            $table->index(['tenant_id', 'status', 'department_id'], 'idx_job_openings_tenant_status_dept');
        });

        Schema::table('candidates', function (Blueprint $table) {
            // Index for source effectiveness (if source column exists)
            if (Schema::hasColumn('candidates', 'source')) {
                $table->index(['tenant_id', 'source'], 'idx_candidates_tenant_source');
            }
        });

        Schema::table('job_stages', function (Blueprint $table) {
            // Index for stage ordering
            $table->index(['tenant_id', 'job_opening_id', 'sort_order'], 'idx_job_stages_tenant_job_sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_applications_tenant_applied_at');
            $table->dropIndex('idx_applications_tenant_status_applied_at');
            $table->dropIndex('idx_applications_tenant_stage_applied_at');
            $table->dropIndex('idx_applications_tenant_status_stage');
        });

        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropIndex('idx_job_openings_tenant_status_dept');
        });

        Schema::table('candidates', function (Blueprint $table) {
            if (Schema::hasColumn('candidates', 'source')) {
                $table->dropIndex('idx_candidates_tenant_source');
            }
        });

        Schema::table('job_stages', function (Blueprint $table) {
            $table->dropIndex('idx_job_stages_tenant_job_sort');
        });
    }
};
