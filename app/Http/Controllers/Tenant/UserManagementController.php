<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TenantRole;
use App\Mail\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(string $tenant)
    {
        $tenantModel = tenant();
        
        // Get all users for this tenant
        $users = User::whereHas('tenants', function ($query) use ($tenantModel) {
            $query->where('tenant_id', $tenantModel->id);
        })->paginate(20);

        // Load tenant-specific roles for each user
        $users->getCollection()->each(function ($user) use ($tenantModel) {
            $user->tenant_roles = $user->getRolesForTenant($tenantModel->id);
        });

        // Get available roles for this tenant
        $roles = TenantRole::forTenant($tenantModel->id)->get();

        // Get subscription limit information
        $currentPlan = $tenantModel->currentPlan();
        $currentUserCount = $tenantModel->users()->count();
        $maxUsers = $currentPlan ? $currentPlan->max_users : 0;
        $canAddUsers = $maxUsers === -1 || $currentUserCount < $maxUsers;

        return view('tenant.settings.team', compact('users', 'roles', 'tenant', 'tenantModel', 'currentUserCount', 'maxUsers', 'canAddUsers'));
    }

    public function store(Request $request, string $tenant)
    {
        $tenantModel = tenant();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($tenantModel) {
                    $role = TenantRole::where('id', $value)
                        ->where('tenant_id', $tenantModel->id)
                        ->first();
                    if (!$role) {
                        $fail('The selected role is not valid for this tenant.');
                        return;
                    }
                    
                    // Check if there are no owners and trying to create a non-owner
                    $ownerCount = User::whereHas('tenants', function ($query) use ($tenantModel) {
                        $query->where('tenants.id', $tenantModel->id);
                    })->whereHas('roles', function ($query) use ($tenantModel) {
                        $query->where('roles.name', 'Owner')->where('roles.tenant_id', $tenantModel->id);
                    })->count();
                    
                    if ($ownerCount === 0 && $role->name !== 'Owner') {
                        $fail('The first user must be assigned the Owner role.');
                    }
                },
            ],
            'send_invitation' => 'boolean',
        ]);

        // Create user without password (invitation system)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(12)), // Temporary password
            'is_invited' => true,
        ]);

        // Add user to tenant
        $user->tenants()->attach($tenantModel->id);

        // Assign role
        $role = TenantRole::find($validated['role']);
        $user->assignRole($role);

        // Generate invitation token and send email if requested
        $emailSent = false;
        if ($request->has('send_invitation')) {
            $invitationToken = $user->generateInvitationToken();
            $emailSent = $this->sendInvitationEmail($user, $tenantModel, $role->name, $invitationToken);
        }

        $message = 'User created successfully.';
        if ($request->has('send_invitation')) {
            if ($emailSent) {
                $message .= ' Invitation email sent successfully.';
            } else {
                $message .= ' Note: Invitation email could not be sent. Please contact the user directly.';
            }
        }

        return redirect()
            ->route('tenant.settings.team', $tenant)
            ->with('success', $message);
    }

    public function update(Request $request, string $tenant, User $user)
    {
        $tenantModel = tenant();
        
        // Ensure user belongs to this tenant
        if (!$user->belongsToTenant($tenantModel->id)) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'role' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($tenantModel, $user) {
                    $role = TenantRole::where('id', $value)
                        ->where('tenant_id', $tenantModel->id)
                        ->first();
                    if (!$role) {
                        $fail('The selected role is not valid for this tenant.');
                        return;
                    }
                    
                    // Check if trying to change the last owner's role
                    if ($user->hasRole('Owner') && $this->isLastOwner($user, $tenantModel) && $role->name !== 'Owner') {
                        $fail('Cannot change the role of the last owner. There must be at least one owner in the organization.');
                    }
                },
            ],
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update role
        $role = TenantRole::find($validated['role']);
        $user->syncRoles([$role]);

        return redirect()
            ->route('tenant.settings.team', $tenant)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(string $tenant, User $user)
    {
        $tenantModel = tenant();
        
        // Ensure user belongs to this tenant
        if (!$user->belongsToTenant($tenantModel->id)) {
            abort(403, 'Unauthorized');
        }

        // Don't allow deleting the last owner
        if ($user->hasRole('Owner') && $this->isLastOwner($user, $tenantModel)) {
            return redirect()
                ->route('tenant.settings.team', $tenant)
                ->with('error', 'Cannot delete the last owner of the organization.');
        }

        // Remove user from tenant
        $user->tenants()->detach($tenantModel->id);

        // If user has no other tenants, delete the user
        if ($user->tenants()->count() === 0) {
            $user->delete();
        }

        return redirect()
            ->route('tenant.settings.team', $tenant)
            ->with('success', 'User removed successfully.');
    }

    public function resendInvitation(string $tenant, User $user)
    {
        $tenantModel = tenant();
        
        // Ensure user belongs to this tenant
        if (!$user->belongsToTenant($tenantModel->id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $userRole = $user->roles->first();
        $invitationToken = $user->generateInvitationToken();
        $emailSent = $this->sendInvitationEmail($user, $tenantModel, $userRole->name, $invitationToken);

        // Always return JSON for resend invitation (it's only called via AJAX)
        if ($emailSent) {
            return response()->json(['success' => true, 'message' => 'Invitation sent successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to send invitation email. Please try again.']);
        }
    }

    public function toggleStatus(string $tenant, User $user)
    {
        $tenantModel = tenant();
        
        // Ensure user belongs to this tenant
        if (!$user->belongsToTenant($tenantModel->id)) {
            abort(403, 'Unauthorized');
        }

        // Don't allow deactivating the last owner
        if ($user->hasRole('Owner') && $this->isLastOwner($user, $tenantModel)) {
            return redirect()
                ->route('tenant.settings.team', $tenant)
                ->with('error', 'Cannot deactivate the last owner of the organization.');
        }

        // Toggle user status (you might want to add an 'is_active' field to users table)
        // For now, we'll just show a message
        return redirect()
            ->route('tenant.settings.team', $tenant)
            ->with('success', 'User status updated successfully.');
    }

    private function sendInvitationEmail(User $user, $tenant, string $roleName, string $invitationToken = null)
    {
        try {
            Mail::to($user->email)->send(new TeamInvitation($tenant, $user, $roleName, $invitationToken));
            \Log::info("Invitation sent to {$user->email} for tenant {$tenant->name} with role {$roleName}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send invitation to {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    private function isLastOwner(User $user, $tenant)
    {
        $ownerCount = User::whereHas('tenants', function ($query) use ($tenant) {
            $query->where('tenants.id', $tenant->id);
        })->whereHas('roles', function ($query) use ($tenant) {
            $query->where('roles.name', 'Owner')->where('roles.tenant_id', $tenant->id);
        })->count();

        return $ownerCount <= 1;
    }
}
