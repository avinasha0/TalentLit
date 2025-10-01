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
        // Drop the existing unique constraint
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_tenant_name_guard_unique');
        });

        // Change tenant_id from unsignedBigInteger to uuid
        Schema::table('roles', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->change();
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->change();
        });

        // Recreate the unique constraint with the correct data type
        Schema::table('roles', function (Blueprint $table) {
            $table->unique(['tenant_id', 'name', 'guard_name'], 'roles_tenant_name_guard_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the unique constraint
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_tenant_name_guard_unique');
        });

        // Change back to unsignedBigInteger
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });

        // Recreate the original unique constraint
        Schema::table('roles', function (Blueprint $table) {
            $table->unique(['tenant_id', 'name', 'guard_name'], 'roles_tenant_name_guard_unique');
        });
    }
};