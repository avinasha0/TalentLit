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
        // Drop task_title column if it exists (we use 'title' instead)
        if (Schema::hasColumn('tasks', 'task_title')) {
            DB::statement('ALTER TABLE tasks DROP COLUMN task_title');
        }
        
        // Ensure title column exists and is nullable
        if (!Schema::hasColumn('tasks', 'title')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('title', 255)->nullable()->after('task_type');
            });
        } else {
            // Make sure title is nullable
            try {
                DB::statement('ALTER TABLE tasks MODIFY COLUMN title VARCHAR(255) NULL');
            } catch (\Exception $e) {
                // Ignore if already nullable or other error
            }
        }
        
        Schema::table('tasks', function (Blueprint $table) {
            // Add created_by column if it doesn't exist
            if (!Schema::hasColumn('tasks', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('link');
            }
            
            // Add tenant_id column if it doesn't exist
            if (!Schema::hasColumn('tasks', 'tenant_id')) {
                $table->uuid('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'tenant_id')) {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            }
            
            if (Schema::hasColumn('tasks', 'created_by')) {
                $table->dropColumn('created_by');
            }
            
            if (Schema::hasColumn('tasks', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
};
