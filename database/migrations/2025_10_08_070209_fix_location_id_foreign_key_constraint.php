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
        // First, check if the foreign key constraint exists and drop it
        $constraintName = 'job_openings_location_id_foreign';
        
        // Get all foreign key constraints for the job_openings table
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'job_openings' 
            AND COLUMN_NAME = 'location_id' 
            AND CONSTRAINT_NAME != 'PRIMARY'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        // Drop each foreign key constraint found
        foreach ($foreignKeys as $fk) {
            $constraintName = $fk->CONSTRAINT_NAME;
            try {
                DB::statement("ALTER TABLE job_openings DROP FOREIGN KEY `{$constraintName}`");
            } catch (Exception $e) {
                // Constraint might not exist, continue
            }
        }
        
        // Check if location_id column exists before trying to drop it
        $columnExists = DB::select("
            SELECT COLUMN_NAME 
            FROM information_schema.COLUMNS 
            WHERE TABLE_NAME = 'job_openings' 
            AND COLUMN_NAME = 'location_id'
        ");
        
        if (count($columnExists) > 0) {
            // Now drop the column
            Schema::table('job_openings', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            $table->uuid('location_id')->after('department_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->index(['tenant_id', 'location_id']);
        });
    }
};