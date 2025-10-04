<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the missing 'manage email templates' permission
        Permission::firstOrCreate([
            'name' => 'manage email templates',
            'guard_name' => 'web'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the permission
        Permission::where('name', 'manage email templates')
            ->where('guard_name', 'web')
            ->delete();
    }
};