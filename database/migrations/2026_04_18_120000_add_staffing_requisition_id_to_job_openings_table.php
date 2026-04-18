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
        Schema::table('job_openings', function (Blueprint $table) {
            $table->unsignedBigInteger('staffing_requisition_id')->nullable()->after('requisition_id');
            $table->unique('staffing_requisition_id', 'job_openings_staffing_requisition_id_unique');
            $table->foreign('staffing_requisition_id')
                ->references('id')
                ->on('requisitions')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropForeign(['staffing_requisition_id']);
            $table->dropUnique('job_openings_staffing_requisition_id_unique');
            $table->dropColumn('staffing_requisition_id');
        });
    }
};
