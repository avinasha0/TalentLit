<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\PermissionService;
use Illuminate\Console\Command;

class UpdateTenantRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:update-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all tenant roles to match the exact permission matrix';

    /**
     * Execute the console command.
     */
    public function handle(PermissionService $permissionService): int
    {
        $this->info('Updating tenant roles to match exact permission matrix...');

        $tenants = Tenant::all();
        $updated = 0;

        foreach ($tenants as $tenant) {
            $this->line("Updating roles for tenant: {$tenant->name} ({$tenant->id})");
            $permissionService->ensureTenantRoles($tenant->id);
            $updated++;
        }

        $this->info("Successfully updated roles for {$updated} tenant(s).");

        return Command::SUCCESS;
    }
}

