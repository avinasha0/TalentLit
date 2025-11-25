# Subdomain Features for Enterprise Plans - Implementation Plan

## Overview
This document outlines the strategy for implementing subdomain support for Enterprise plan tenants while maintaining full backward compatibility with the existing path-based routing system.

## Current Architecture
- **Path-based Tenancy**: Routes use `/{tenant}/dashboard` pattern
- **Tenant Resolution**: `ResolveTenantFromPath` middleware resolves tenant from URL slug
- **Enterprise Plan**: Already exists with unlimited features, but no subdomain support

## Goals
1. ✅ Add subdomain support for Enterprise plan tenants
2. ✅ Maintain 100% backward compatibility with path-based routing
3. ✅ Allow testing of existing functions after implementation
4. ✅ Zero impact on Free and Pro plan tenants

---

## Architecture Strategy

### 1. Dual Routing System
Implement a **dual routing system** that supports both:
- **Path-based routing** (existing): `example.com/{tenant}/dashboard`
- **Subdomain routing** (new): `{tenant}.example.com/dashboard`

### 2. Tenant Resolution Priority
```
1. Check subdomain first (if Enterprise plan)
2. Fall back to path-based routing (existing behavior)
3. Ensure both methods work independently
```

---

## Implementation Plan

### Phase 1: Database Schema Changes

#### 1.1 Add Subdomain Field to Tenants Table
**File**: `database/migrations/YYYY_MM_DD_HHMMSS_add_subdomain_to_tenants_table.php`

```php
Schema::table('tenants', function (Blueprint $table) {
    $table->string('subdomain')->nullable()->unique()->after('slug');
    $table->boolean('subdomain_enabled')->default(false)->after('subdomain');
    $table->timestamp('subdomain_verified_at')->nullable()->after('subdomain_enabled');
});
```

**Why**: 
- `subdomain`: Stores the subdomain value (e.g., "acme" for acme.example.com)
- `subdomain_enabled`: Flag to enable/disable subdomain access
- `subdomain_verified_at`: Track when subdomain was verified/configured

#### 1.2 Add Subdomain Index
Create unique index on `subdomain` for fast lookups.

---

### Phase 2: Middleware Development

#### 2.1 Create New Subdomain Middleware
**File**: `app/Http/Middleware/ResolveTenantFromSubdomain.php`

**Purpose**: Resolve tenant from subdomain for Enterprise plans only.

**Logic**:
```php
1. Extract subdomain from request host
2. Check if subdomain exists in tenants table
3. Verify tenant has Enterprise plan
4. Verify subdomain_enabled = true
5. Set tenant context (same as ResolveTenantFromPath)
6. Continue to next middleware
```

**Key Features**:
- Only processes requests with subdomain
- Validates Enterprise plan subscription
- Falls through silently if not subdomain request (doesn't break path-based routing)
- Uses same Tenancy::set() method for consistency

#### 2.2 Update Bootstrap Configuration
**File**: `bootstrap/app.php`

**Changes**:
- Register new middleware: `'subdomain.tenant' => ResolveTenantFromSubdomain::class`
- Add middleware to web group **BEFORE** `ResolveTenantFromPath`
- Order matters: Subdomain → Path-based (fallback)

---

### Phase 3: Route Configuration

#### 3.1 Create Subdomain Route Group
**File**: `routes/web.php`

**Strategy**: Create parallel route groups

**Structure**:
```php
// Existing path-based routes (UNCHANGED)
Route::middleware(['capture.tenant', 'tenant', 'auth'])->group(function () {
    Route::get('/{tenant}/dashboard', ...);
    // ... all existing routes remain unchanged
});

// NEW: Subdomain routes (Enterprise only)
Route::domain('{subdomain}.' . config('app.domain'))
    ->middleware(['subdomain.tenant', 'auth'])
    ->group(function () {
        Route::get('/dashboard', ...); // No {tenant} in path
        Route::get('/jobs', ...);
        // ... mirror all tenant routes without {tenant} parameter
    });
```

**Key Points**:
- Subdomain routes don't include `{tenant}` in path
- Use same controller methods
- Same middleware stack (except tenant resolution)
- All existing routes remain untouched

---

### Phase 4: Model Updates

#### 4.1 Update Tenant Model
**File**: `app/Models/Tenant.php`

**Add**:
```php
protected $fillable = [
    // ... existing fields
    'subdomain',
    'subdomain_enabled',
    'subdomain_verified_at',
];

// New methods
public function hasSubdomainEnabled(): bool
{
    return $this->subdomain_enabled 
        && $this->subdomain 
        && $this->hasEnterprisePlan();
}

public function hasEnterprisePlan(): bool
{
    $plan = $this->currentPlan();
    return $plan && $plan->slug === 'enterprise';
}
```

---

### Phase 5: Controller Updates

#### 5.1 Update Route Parameter Handling
**Strategy**: Make controllers subdomain-aware

**Approach**: 
- Controllers already use `Tenancy::get()` to get current tenant
- No changes needed to controller logic
- Route parameter `{tenant}` only exists in path-based routes
- Subdomain routes don't pass `{tenant}` parameter

**Example**:
```php
// Existing controller (works for both)
public function index()
{
    $tenant = Tenancy::get(); // Works for both routing methods
    // ... rest of logic unchanged
}
```

---

### Phase 6: URL Generation

#### 6.1 Create URL Helper
**File**: `app/Helpers/SubdomainHelper.php` (or add to existing helpers)

**Purpose**: Generate URLs that respect routing method

**Methods**:
```php
public static function route(string $name, array $parameters = []): string
{
    $tenant = Tenancy::get();
    
    if ($tenant && $tenant->hasSubdomainEnabled()) {
        // Generate subdomain URL
        return url($path); // Simple path, no tenant slug
    } else {
        // Generate path-based URL (existing behavior)
        return route($name, array_merge(['tenant' => $tenant->slug], $parameters));
    }
}
```

#### 6.2 Update Views
**Strategy**: Use helper function in views

**Before**:
```blade
{{ route('tenant.dashboard', $tenant->slug) }}
```

**After**:
```blade
{{ SubdomainHelper::route('tenant.dashboard') }}
```

**Note**: Existing views continue to work (backward compatible)

---

### Phase 7: Settings UI

#### 7.1 Add Subdomain Settings
**File**: `resources/views/tenant/settings/general.blade.php`

**Add Section**:
- Subdomain input field (Enterprise plan only)
- Enable/disable toggle
- Validation message
- DNS instructions

**Controller**: `app/Http/Controllers/Tenant/GeneralSettingsController.php`

**Validation**:
- Only Enterprise plan can set subdomain
- Subdomain must be unique
- Subdomain format validation (alphanumeric, hyphens)
- Reserved subdomain check (www, api, admin, etc.)

---

### Phase 8: DNS & Server Configuration

#### 8.1 Wildcard DNS Setup
**Requirement**: Configure wildcard DNS for subdomains

**Example**:
```
*.example.com → Server IP
```

#### 8.2 Web Server Configuration
**Apache/Nginx**: Configure to accept wildcard subdomains

**Apache**:
```apache
<VirtualHost *:80>
    ServerName example.com
    ServerAlias *.example.com
    DocumentRoot /path/to/public
</VirtualHost>
```

**Nginx**:
```nginx
server {
    server_name ~^(.+)\.example\.com$;
    root /path/to/public;
}
```

---

## Testing Strategy

### Test Cases

#### 1. Backward Compatibility Tests
- ✅ Path-based routing still works: `/{tenant}/dashboard`
- ✅ Free plan tenants: Path-based only
- ✅ Pro plan tenants: Path-based only
- ✅ Enterprise without subdomain: Path-based only

#### 2. Subdomain Functionality Tests
- ✅ Enterprise with subdomain: `{subdomain}.example.com/dashboard`
- ✅ Enterprise can access both methods
- ✅ Subdomain validation (unique, format)
- ✅ Reserved subdomain rejection

#### 3. Integration Tests
- ✅ Same tenant accessible via both methods
- ✅ Session persistence across routing methods
- ✅ Permission checks work in both modes
- ✅ Subscription limits enforced in both modes

#### 4. Edge Cases
- ✅ Invalid subdomain → 404
- ✅ Subdomain for non-Enterprise → 404
- ✅ Disabled subdomain → fallback to path-based
- ✅ Missing subdomain in request → path-based routing

---

## Migration Path

### Step 1: Database Migration
```bash
php artisan make:migration add_subdomain_to_tenants_table
php artisan migrate
```

### Step 2: Deploy Middleware
- Add `ResolveTenantFromSubdomain` middleware
- Register in `bootstrap/app.php`
- Test in isolation

### Step 3: Add Routes
- Add subdomain route group
- Test subdomain routing
- Verify path-based routing still works

### Step 4: Update Settings UI
- Add subdomain configuration
- Test Enterprise plan subdomain setup

### Step 5: DNS Configuration
- Configure wildcard DNS
- Test subdomain access

---

## Rollback Plan

If issues arise:

1. **Disable Subdomain Middleware**: Comment out in `bootstrap/app.php`
2. **Remove Subdomain Routes**: Comment out subdomain route group
3. **Database**: Subdomain fields are nullable, safe to ignore
4. **No Data Loss**: All existing functionality remains intact

---

## File Structure

### New Files
```
app/Http/Middleware/ResolveTenantFromSubdomain.php
app/Helpers/SubdomainHelper.php (optional)
database/migrations/YYYY_MM_DD_HHMMSS_add_subdomain_to_tenants_table.php
```

### Modified Files
```
bootstrap/app.php (middleware registration)
routes/web.php (add subdomain route group)
app/Models/Tenant.php (add subdomain fields and methods)
app/Http/Controllers/Tenant/GeneralSettingsController.php (subdomain settings)
resources/views/tenant/settings/general.blade.php (subdomain UI)
```

### Unchanged Files
```
app/Http/Middleware/ResolveTenantFromPath.php (NO CHANGES)
All existing controllers (NO CHANGES)
All existing views (backward compatible)
```

---

## Security Considerations

1. **Subdomain Validation**: Prevent reserved subdomains (www, api, admin, mail, etc.)
2. **Enterprise Plan Check**: Enforce Enterprise plan requirement
3. **Subdomain Uniqueness**: Database unique constraint
4. **Rate Limiting**: Apply to subdomain routes
5. **CORS**: Configure if needed for subdomain API access

---

## Performance Considerations

1. **Database Index**: Index on `subdomain` column for fast lookups
2. **Caching**: Cache tenant lookup by subdomain
3. **Middleware Order**: Subdomain check before path-based (faster for subdomain requests)

---

## Configuration

### Environment Variables
```env
APP_DOMAIN=example.com
SUBDOMAIN_ENABLED=true
```

### Config File
**File**: `config/tenancy.php` (new)

```php
return [
    'domain' => env('APP_DOMAIN', 'localhost'),
    'subdomain_enabled' => env('SUBDOMAIN_ENABLED', false),
    'reserved_subdomains' => ['www', 'api', 'admin', 'mail', 'ftp'],
];
```

---

## Success Criteria

✅ Enterprise tenants can access via subdomain
✅ Path-based routing works for all tenants
✅ No breaking changes to existing functionality
✅ All tests pass (existing + new)
✅ Settings UI allows subdomain configuration
✅ DNS properly configured

---

## Implementation Checklist

- [ ] Create database migration
- [ ] Add subdomain fields to Tenant model
- [ ] Create ResolveTenantFromSubdomain middleware
- [ ] Register middleware in bootstrap/app.php
- [ ] Create subdomain route group
- [ ] Update GeneralSettingsController
- [ ] Add subdomain UI to settings page
- [ ] Create SubdomainHelper (optional)
- [ ] Write tests for subdomain functionality
- [ ] Write tests for backward compatibility
- [ ] Configure DNS (wildcard)
- [ ] Configure web server
- [ ] Test in staging environment
- [ ] Deploy to production

---

## Notes

1. **Zero Downtime**: All changes are additive, no existing code modified
2. **Feature Flag**: Can enable/disable subdomain feature via config
3. **Gradual Rollout**: Enable for specific Enterprise tenants first
4. **Monitoring**: Track subdomain vs path-based usage

---

## Questions to Consider

1. Should Enterprise tenants be able to use both methods simultaneously?
2. Should we redirect path-based to subdomain for Enterprise tenants?
3. How to handle SSL certificates for subdomains?
4. Should subdomain be required or optional for Enterprise?

---

## Next Steps

1. Review and approve this plan
2. Create feature branch: `feature/enterprise-subdomain`
3. Start with Phase 1 (database migration)
4. Test each phase before proceeding
5. Maintain backward compatibility throughout

