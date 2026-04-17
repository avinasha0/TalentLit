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
        // Check if title column already exists
        if (!Schema::hasColumn('tasks', 'title')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('title', 255)->nullable()->after('task_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tasks', 'title')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
    }
};
