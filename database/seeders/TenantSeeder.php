<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Acme tenant
        $tenant = Tenant::create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        // Create admin user for Acme
        $admin = User::create([
            'name' => 'Acme Admin',
            'email' => 'admin@acme.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Assign Owner role to admin
        $admin->assignRole('Owner');
    }
}
