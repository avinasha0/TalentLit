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
        // Remove the old unique constraint that only includes name and guard_name
        // This allows multiple roles with the same name for different tenants
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name', 'guard_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the old unique constraint
        Schema::table('roles', function (Blueprint $table) {
            $table->unique(['name', 'guard_name']);
        });
    }
};