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
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            if (!Schema::hasColumn('job_openings', 'location_id')) {
                return;
            }

            Schema::disableForeignKeyConstraints();
            try {
                Schema::table('job_openings', function (Blueprint $table) {
                    try {
                        $table->dropForeign(['location_id']);
                    } catch (\Throwable $e) {
                        // SQLite may not expose a named FK for this column
                    }
                });
                foreach (Schema::getIndexes('job_openings') as $index) {
                    if (in_array('location_id', $index['columns'] ?? [], true)) {
                        Schema::table('job_openings', function (Blueprint $table) use ($index) {
                            $table->dropIndex($index['name']);
                        });
                    }
                }
                Schema::table('job_openings', function (Blueprint $table) {
                    $table->dropColumn('location_id');
                });
            } finally {
                Schema::enableForeignKeyConstraints();
            }

            return;
        }

        // First, check if the foreign key constraint exists and drop it
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
        
        // Now drop the column
        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropColumn('location_id');
        });
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