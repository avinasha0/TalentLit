<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanyOrgStructureSeeder extends Seeder
{
    /**
     * Seed 20 members for a tenant with realistic org structure.
     */
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'aavi10111')->first()
            ?? Tenant::firstOrCreate(
                ['slug' => 'acme'],
                ['name' => 'Acme']
            );

        // Ensure tenant-scoped custom roles exist before assigning user roles.
        app(\App\Services\PermissionService::class)->ensureTenantRoles($tenant->id);

        $members = [
            ['name' => 'Aarav Mehta', 'email' => 'aarav.mehta@acme.com', 'role' => 'Owner'],
            ['name' => 'Nisha Kapoor', 'email' => 'nisha.kapoor@acme.com', 'role' => 'Admin'],
            ['name' => 'Rahul Verma', 'email' => 'rahul.verma@acme.com', 'role' => 'Admin'],
            ['name' => 'Priya Nair', 'email' => 'priya.nair@acme.com', 'role' => 'Hiring Manager'],
            ['name' => 'Arjun Singh', 'email' => 'arjun.singh@acme.com', 'role' => 'Hiring Manager'],
            ['name' => 'Sneha Iyer', 'email' => 'sneha.iyer@acme.com', 'role' => 'Hiring Manager'],
            ['name' => 'Vikram Rao', 'email' => 'vikram.rao@acme.com', 'role' => 'Hiring Manager'],
            ['name' => 'Kavya Shah', 'email' => 'kavya.shah@acme.com', 'role' => 'Recruiter'],
            ['name' => 'Rohan Desai', 'email' => 'rohan.desai@acme.com', 'role' => 'Recruiter'],
            ['name' => 'Ishita Gupta', 'email' => 'ishita.gupta@acme.com', 'role' => 'Recruiter'],
            ['name' => 'Aditya Bansal', 'email' => 'aditya.bansal@acme.com', 'role' => 'Recruiter'],
            ['name' => 'Neha Joshi', 'email' => 'neha.joshi@acme.com', 'role' => 'Recruiter'],
            ['name' => 'Karan Malhotra', 'email' => 'karan.malhotra@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Ananya Pillai', 'email' => 'ananya.pillai@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Dev Patel', 'email' => 'dev.patel@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Pooja Menon', 'email' => 'pooja.menon@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Siddharth Jain', 'email' => 'siddharth.jain@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Tanya Arora', 'email' => 'tanya.arora@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Manish Kulkarni', 'email' => 'manish.kulkarni@acme.com', 'role' => 'Interviewer'],
            ['name' => 'Ritika Sen', 'email' => 'ritika.sen@acme.com', 'role' => 'Interviewer'],
        ];

        foreach ($members as $member) {
            $user = User::updateOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Keep tenant membership idempotent.
            $user->tenants()->syncWithoutDetaching([$tenant->id]);
            $user->assignRoleForTenant($member['role'], $tenant->id);
        }

        $this->command?->info('Seeded 20 company members with org roles for tenant: '.$tenant->name);
        $this->command?->line('Org structure: 1 Owner, 2 Admins, 4 Hiring Managers, 5 Recruiters, 8 Interviewers');
    }
}
