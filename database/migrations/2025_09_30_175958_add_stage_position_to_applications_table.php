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
        Schema::table('applications', function (Blueprint $table) {
            $table->integer('stage_position')->default(0)->after('current_stage_id');
            $table->index(['tenant_id', 'job_opening_id', 'current_stage_id', 'stage_position'], 'applications_stage_position_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_stage_position_index');
            $table->dropColumn('stage_position');
        });
    }
};
