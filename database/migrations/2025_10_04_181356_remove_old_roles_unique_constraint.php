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
        if (!Schema::hasTable('roles')) {
            return;
        }

        // Remove the old unique constraint that only includes name and guard_name
        // This allows multiple roles with the same name for different tenants.
        // SQLite may not expose the same index name as MySQL; ignore missing index.
        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique(['name', 'guard_name']);
            });
        } catch (\Throwable $e) {
            $msg = strtolower($e->getMessage());
            if (!str_contains($msg, 'no such index') && !str_contains($msg, "can't drop")) {
                throw $e;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('roles')) {
            return;
        }

        try {
            Schema::table('roles', function (Blueprint $table) {
                $table->unique(['name', 'guard_name']);
            });
        } catch (\Throwable $e) {
            $msg = strtolower($e->getMessage());
            if (!str_contains($msg, 'already exists') && !str_contains($msg, 'duplicate')) {
                throw $e;
            }
        }
    }
};