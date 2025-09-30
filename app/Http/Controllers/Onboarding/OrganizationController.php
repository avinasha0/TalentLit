<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    public function create()
    {
        return view('onboarding.organization');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:tenants,slug', 'regex:/^[a-z0-9-]+$/'],
            'website' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:255'],
        ]);

        // Create the tenant
        $tenant = Tenant::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'website' => $request->website,
            'location' => $request->location,
            'company_size' => $request->company_size,
        ]);

        // Add current user to tenant
        $user = auth()->user();
        $user->tenants()->attach($tenant->id);

        // Create roles and permissions for this tenant
        $this->createRolesForTenant($tenant);

        // Assign Owner role to the user
        $ownerRole = TenantRole::where('tenant_id', $tenant->id)->where('name', 'Owner')->first();
        if ($ownerRole) {
            $user->assignRole($ownerRole);
        }

        // Set session data
        session(['current_tenant_id' => $tenant->id]);
        session(['last_tenant_slug' => $tenant->slug]);

        return redirect()->route('onboarding.setup', $tenant->slug);
    }

    private function createRolesForTenant(Tenant $tenant): void
    {
        // Create Owner role
        $ownerRole = TenantRole::create([
            'name' => 'Owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create Admin role
        $adminRole = TenantRole::create([
            'name' => 'Admin',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create Recruiter role
        $recruiterRole = TenantRole::create([
            'name' => 'Recruiter',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create Hiring Manager role
        $hiringManagerRole = TenantRole::create([
            'name' => 'Hiring Manager',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        // Create basic permissions
        $permissions = [
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard', 'manage users', 'manage settings'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign all permissions to Owner role
        $ownerRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Assign limited permissions to other roles
        $adminRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard'
        ]);

        $recruiterRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'publish jobs', 'close jobs',
            'view stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'move candidates',
            'view dashboard'
        ]);

        $hiringManagerRole->syncPermissions([
            'view jobs', 'view stages', 'view candidates', 'view dashboard'
        ]);
    }
}