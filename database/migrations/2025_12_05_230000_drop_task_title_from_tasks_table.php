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
        // Drop task_title column if it exists
        if (Schema::hasColumn('tasks', 'task_title')) {
            DB::statement('ALTER TABLE tasks DROP COLUMN task_title');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add task_title column if needed (for rollback)
        if (!Schema::hasColumn('tasks', 'task_title')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('task_title', 255)->nullable()->after('task_type');
            });
        }
    }
};
