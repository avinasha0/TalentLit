# Employee Onboarding Module - Quick Reference

## Implementation Steps Summary

### Step 1: Database Configuration (15 minutes)
1. Create migration: `add_employee_onboarding_enabled_to_tenants_table`
2. Add field to `Tenant` model `$fillable`
3. Add helper method `isEmployeeOnboardingEnabled()` to Tenant model
4. Run migration

### Step 2: Middleware Creation (20 minutes)
1. Create `app/Http/Middleware/EmployeeOnboardingEnabled.php`
2. Register in `bootstrap/app.php` as `employee.onboarding.enabled`
3. Test middleware logic

### Step 3: Route Protection (30 minutes)
1. Wrap existing routes with `employee.onboarding.enabled` middleware
2. Group routes under `prefix('employee-onboarding')`
3. Update both path-based and subdomain routes
4. Test route access when disabled/enabled

### Step 4: Navigation Updates (20 minutes)
1. Update `sidebar.blade.php` with conditional `@if($tenant->employee_onboarding_enabled)`
2. Update `mobile-menu.blade.php` with same condition
3. Remove "Under Development" badge
4. Test navigation visibility

### Step 5: Settings Integration (30 minutes)
1. Add `employee_onboarding_enabled` to `GeneralSettingsController` validation
2. Add field to update logic
3. Add toggle switch to `general.blade.php` view
4. Test toggle functionality

### Step 6: Directory Structure (30 minutes)
1. Create `app/Http/Controllers/Tenant/EmployeeOnboarding/` directory
2. Create `app/Models/EmployeeOnboarding/` directory
3. Create `app/Services/EmployeeOnboarding/` directory
4. Create `app/Requests/EmployeeOnboarding/` directory
5. Create `app/Policies/EmployeeOnboarding/` directory
6. Create view subdirectories under `resources/views/tenant/employee-onboarding/`

### Step 7: Move Existing Files (15 minutes)
1. Move `EmployeeOnboardingController.php` to new directory
2. Update namespace
3. Update view paths if needed

## Configuration Checkpoints

### Before Implementation
- [ ] Backup database
- [ ] Review existing Employee Onboarding code
- [ ] Understand tenant system
- [ ] Understand permission system

### During Implementation
- [ ] Test with module disabled (should redirect)
- [ ] Test with module enabled (should work)
- [ ] Test navigation visibility
- [ ] Test settings toggle
- [ ] Test permissions (if applicable)

### After Implementation
- [ ] All routes protected
- [ ] Navigation conditional
- [ ] Settings toggle works
- [ ] No broken links
- [ ] No console errors
- [ ] Mobile menu updated

## Code Snippets

### Middleware Check
```php
// In any controller or view
if (!tenant()->isEmployeeOnboardingEnabled()) {
    abort(403, 'Employee Onboarding module is not enabled');
}
```

### Route Group Example
```php
Route::middleware(['capture.tenant', 'tenant', 'auth', 'employee.onboarding.enabled'])
    ->prefix('{tenant}/employee-onboarding')
    ->name('tenant.employee-onboarding.')
    ->group(function () {
        Route::get('/', [EmployeeOnboardingController::class, 'index'])->name('index');
    });
```

### Navigation Conditional
```php
@if(tenant()->employee_onboarding_enabled)
    <!-- Navigation item -->
@endif
```

### Settings Validation
```php
'employee_onboarding_enabled' => ['boolean'],
```

### Settings Update
```php
'employee_onboarding_enabled' => $request->boolean('employee_onboarding_enabled'),
```

## Testing Checklist

### Module Disabled
- [ ] Routes redirect to dashboard
- [ ] Navigation item hidden
- [ ] Mobile menu item hidden
- [ ] Direct URL access blocked
- [ ] Error message shown

### Module Enabled
- [ ] Routes accessible
- [ ] Navigation item visible
- [ ] Mobile menu item visible
- [ ] Direct URL access works
- [ ] Settings toggle shows enabled

### Settings Toggle
- [ ] Toggle saves correctly
- [ ] Navigation updates immediately
- [ ] Routes update immediately
- [ ] No page refresh needed (if using Livewire)
- [ ] Works for both path-based and subdomain

## Common Issues & Solutions

### Issue: Routes still accessible when disabled
**Solution**: Check middleware is applied correctly and tenant is resolved

### Issue: Navigation still shows when disabled
**Solution**: Ensure `$tenant->employee_onboarding_enabled` is checked, not just `$tenant`

### Issue: Settings toggle doesn't save
**Solution**: Check validation rules and update logic in controller

### Issue: Subdomain routes not working
**Solution**: Ensure middleware is applied to subdomain route group

### Issue: Permission errors
**Solution**: Check if permissions are required and user has them

## File Locations Quick Reference

| Component | Location |
|-----------|----------|
| Migration | `database/migrations/` |
| Model | `app/Models/Tenant.php` (modify) |
| Middleware | `app/Http/Middleware/EmployeeOnboardingEnabled.php` |
| Routes | `routes/web.php` (modify) |
| Controller | `app/Http/Controllers/Tenant/EmployeeOnboarding/` |
| Views | `resources/views/tenant/employee-onboarding/` |
| Sidebar | `resources/views/components/sidebar.blade.php` |
| Settings | `resources/views/tenant/settings/general.blade.php` |
| Settings Controller | `app/Http/Controllers/Tenant/GeneralSettingsController.php` |

## Environment Variables

No new environment variables needed. Configuration is stored in database per tenant.

## Database Fields

| Table | Field | Type | Default |
|-------|-------|------|---------|
| `tenants` | `employee_onboarding_enabled` | boolean | false |

## Permissions (Future)

When implementing full functionality, consider these permissions:
- `view_employee_onboarding`
- `manage_employee_onboarding`
- `create_employees`
- `edit_employees`
- `delete_employees`
- `manage_onboarding_tasks`
- `manage_onboarding_templates`

## Notes

- Default state: **Disabled** for all tenants
- Can be enabled per tenant via Settings
- No data migration needed (new feature)
- Backward compatible (existing code unaffected)
- Follows same pattern as `careers_enabled`

