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
        Schema::table('job_requisitions', function (Blueprint $table) {
            $table->uuid('global_department_id')->nullable()->after('department_id');
            $table->uuid('global_location_id')->nullable()->after('location_id');
            
            $table->foreign('global_department_id')->references('id')->on('global_departments')->onDelete('set null');
            $table->foreign('global_location_id')->references('id')->on('global_locations')->onDelete('set null');
            
            $table->index(['tenant_id', 'global_department_id']);
            $table->index(['tenant_id', 'global_location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_requisitions', function (Blueprint $table) {
            $table->dropForeign(['global_department_id']);
            $table->dropForeign(['global_location_id']);
            $table->dropIndex(['tenant_id', 'global_department_id']);
            $table->dropIndex(['tenant_id', 'global_location_id']);
            $table->dropColumn(['global_department_id', 'global_location_id']);
        });
    }
};