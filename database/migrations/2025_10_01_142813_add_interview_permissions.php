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
        // Create interview permissions (global, not tenant-specific)
        $permissions = [
            'view interviews',
            'create interviews', 
            'edit interviews',
            'delete interviews',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove interview permissions
        Permission::whereIn('name', [
            'view interviews',
            'create interviews',
            'edit interviews', 
            'delete interviews'
        ])->delete();
    }
};