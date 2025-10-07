<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to handle foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropColumn('location_id');
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            // Recreate the column
            $table->uuid('location_id')->after('department_id');
            // Recreate the foreign key constraint
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            // Recreate the index
            $table->index(['tenant_id', 'location_id']);
        });
    }
};