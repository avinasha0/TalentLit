# Subdomain Feature - Testing Guide

## Quick Reference for Testing Existing Functions

This guide helps you verify that existing functionality remains intact after implementing subdomain features.

---

## Testing Checklist

### 1. Path-Based Routing (Existing Functionality)

#### Test All Existing Routes
```bash
# Dashboard
GET /{tenant}/dashboard

# Jobs
GET /{tenant}/jobs
GET /{tenant}/jobs/create
GET /{tenant}/jobs/{id}

# Candidates
GET /{tenant}/candidates
GET /{tenant}/candidates/{id}

# Interviews
GET /{tenant}/interviews

# Settings
GET /{tenant}/settings/general
GET /{tenant}/settings/team

# Analytics
GET /{tenant}/analytics
```

**Expected Result**: All routes work exactly as before

---

### 2. Subdomain Routing (New Functionality)

#### Test Subdomain Routes (Enterprise Only)
```bash
# Dashboard
GET https://{subdomain}.example.com/dashboard

# Jobs
GET https://{subdomain}.example.com/jobs
GET https://{subdomain}.example.com/jobs/create

# Candidates
GET https://{subdomain}.example.com/candidates

# Settings
GET https://{subdomain}.example.com/settings/general
```

**Expected Result**: Routes work without `/{tenant}/` prefix

---

### 3. Plan-Specific Testing

#### Free Plan Tenant
- ✅ Can access via: `/{tenant}/dashboard`
- ❌ Cannot access via: `{subdomain}.example.com/dashboard` (404)
- ✅ All existing features work

#### Pro Plan Tenant
- ✅ Can access via: `/{tenant}/dashboard`
- ❌ Cannot access via: `{subdomain}.example.com/dashboard` (404)
- ✅ All existing features work

#### Enterprise Plan (No Subdomain)
- ✅ Can access via: `/{tenant}/dashboard`
- ❌ Cannot access via: `{subdomain}.example.com/dashboard` (404)
- ✅ All existing features work

#### Enterprise Plan (With Subdomain)
- ✅ Can access via: `/{tenant}/dashboard` (backward compatible)
- ✅ Can access via: `{subdomain}.example.com/dashboard` (new feature)
- ✅ Both methods work simultaneously
- ✅ All existing features work in both modes

---

## Test Scenarios

### Scenario 1: Existing User Flow (Path-Based)
```
1. User logs in
2. Redirected to /{tenant}/dashboard
3. Navigate to /{tenant}/jobs
4. Create new job
5. View candidates
```

**Expected**: Everything works as before

---

### Scenario 2: Enterprise Subdomain Flow
```
1. Enterprise tenant configures subdomain in settings
2. User accesses {subdomain}.example.com
3. Redirected to {subdomain}.example.com/dashboard
4. Navigate to {subdomain}.example.com/jobs
5. Create new job
6. View candidates
```

**Expected**: All features work via subdomain

---

### Scenario 3: Mixed Access (Enterprise)
```
1. User accesses /{tenant}/dashboard (path-based)
2. User clicks link that uses subdomain URL
3. User accesses {subdomain}.example.com/jobs
4. Session persists across both methods
```

**Expected**: Seamless transition between routing methods

---

### Scenario 4: Non-Enterprise Subdomain Attempt
```
1. Free/Pro tenant tries to access subdomain.example.com
2. Returns 404 or redirects to path-based route
```

**Expected**: Subdomain access denied for non-Enterprise

---

## Automated Test Cases

### PHPUnit Tests

#### Test Path-Based Routing Still Works
```php
public function test_path_based_routing_still_works()
{
    $tenant = Tenant::factory()->create(['slug' => 'test-tenant']);
    $user = User::factory()->create();
    $tenant->users()->attach($user);
    
    $response = $this->actingAs($user)
        ->get("/{$tenant->slug}/dashboard");
    
    $response->assertStatus(200);
}
```

#### Test Subdomain Routing Works (Enterprise)
```php
public function test_subdomain_routing_works_for_enterprise()
{
    $enterprisePlan = SubscriptionPlan::where('slug', 'enterprise')->first();
    $tenant = Tenant::factory()->create([
        'subdomain' => 'test-enterprise',
        'subdomain_enabled' => true,
    ]);
    
    $subscription = TenantSubscription::factory()->create([
        'tenant_id' => $tenant->id,
        'plan_id' => $enterprisePlan->id,
        'status' => 'active',
    ]);
    
    $user = User::factory()->create();
    $tenant->users()->attach($user);
    
    $response = $this->actingAs($user)
        ->get("https://{$tenant->subdomain}.example.com/dashboard");
    
    $response->assertStatus(200);
}
```

#### Test Subdomain Denied for Non-Enterprise
```php
public function test_subdomain_denied_for_free_plan()
{
    $freePlan = SubscriptionPlan::where('slug', 'free')->first();
    $tenant = Tenant::factory()->create([
        'subdomain' => 'test-free',
        'subdomain_enabled' => false,
    ]);
    
    $subscription = TenantSubscription::factory()->create([
        'tenant_id' => $tenant->id,
        'plan_id' => $freePlan->id,
        'status' => 'active',
    ]);
    
    $response = $this->get("https://{$tenant->subdomain}.example.com/dashboard");
    
    $response->assertStatus(404);
}
```

---

## Manual Testing Steps

### Step 1: Test Existing Path-Based Routes
1. Log in as any user (Free/Pro/Enterprise)
2. Access dashboard via: `/{tenant}/dashboard`
3. Navigate through all major sections:
   - Jobs
   - Candidates
   - Interviews
   - Analytics
   - Settings
4. Verify all CRUD operations work
5. Verify permissions work correctly

**Result**: ✅ All existing functionality intact

---

### Step 2: Test Subdomain Configuration (Enterprise Only)
1. Log in as Enterprise tenant owner
2. Go to Settings → General
3. Enter subdomain (e.g., "acme")
4. Enable subdomain
5. Save settings

**Result**: ✅ Subdomain saved and enabled

---

### Step 3: Test Subdomain Access (Enterprise)
1. Configure subdomain for Enterprise tenant
2. Access: `{subdomain}.example.com/dashboard`
3. Verify tenant context is correct
4. Navigate through all sections
5. Verify all features work

**Result**: ✅ Subdomain routing works

---

### Step 4: Test Backward Compatibility
1. Enterprise tenant with subdomain configured
2. Access via path-based: `/{tenant}/dashboard`
3. Verify it still works
4. Switch to subdomain: `{subdomain}.example.com/dashboard`
5. Verify both methods work

**Result**: ✅ Backward compatibility maintained

---

### Step 5: Test Non-Enterprise Access
1. Try accessing subdomain for Free plan tenant
2. Try accessing subdomain for Pro plan tenant
3. Verify 404 or proper error message

**Result**: ✅ Subdomain access properly restricted

---

## Regression Testing

### Critical Functions to Test

#### Authentication & Authorization
- [ ] Login works (path-based)
- [ ] Login works (subdomain)
- [ ] Permissions enforced (path-based)
- [ ] Permissions enforced (subdomain)
- [ ] Role-based access control works

#### Core Features
- [ ] Job creation/editing
- [ ] Candidate management
- [ ] Application processing
- [ ] Interview scheduling
- [ ] Pipeline management
- [ ] Analytics dashboard

#### Subscription Limits
- [ ] Limits enforced (path-based)
- [ ] Limits enforced (subdomain)
- [ ] Enterprise unlimited features work

#### Settings
- [ ] General settings update
- [ ] Team management
- [ ] SMTP configuration
- [ ] Branding settings

---

## Performance Testing

### Load Testing
1. Test path-based routing under load
2. Test subdomain routing under load
3. Compare response times
4. Verify no degradation

### Database Queries
1. Monitor tenant resolution queries
2. Verify subdomain lookup is indexed
3. Check for N+1 query issues

---

## Browser Testing

### Test in Multiple Browsers
- Chrome
- Firefox
- Safari
- Edge

### Test on Multiple Devices
- Desktop
- Tablet
- Mobile

---

## DNS Testing

### Verify DNS Configuration
```bash
# Test wildcard DNS
nslookup *.example.com

# Test specific subdomain
nslookup test-enterprise.example.com
```

---

## SSL Certificate Testing

### HTTPS Testing
1. Verify SSL works for main domain
2. Verify SSL works for subdomains
3. Test wildcard SSL certificate
4. Verify no certificate warnings

---

## Rollback Testing

### If Issues Occur
1. Disable subdomain middleware
2. Verify path-based routing still works
3. Verify no errors in logs
4. Verify all existing features work

---

## Monitoring

### Metrics to Track
- Subdomain vs path-based usage
- Error rates for each routing method
- Response times
- Tenant resolution failures

### Logs to Monitor
- Tenant resolution logs
- Subdomain access attempts
- Failed subdomain lookups
- Enterprise plan verification

---

## Success Criteria

✅ All existing path-based routes work
✅ Subdomain routes work for Enterprise
✅ No breaking changes
✅ All tests pass
✅ Performance maintained
✅ Security maintained

---

## Troubleshooting

### Issue: Subdomain returns 404
**Check**:
- DNS configured correctly
- Web server accepts wildcard subdomains
- Tenant has Enterprise plan
- Subdomain enabled in database
- Middleware registered correctly

### Issue: Path-based routing broken
**Check**:
- Middleware order in bootstrap/app.php
- Route groups not conflicting
- Tenant resolution middleware still active

### Issue: Session not persisting
**Check**:
- Session domain configuration
- Cookie settings
- Same-site cookie settings

---

## Test Data Setup

### Create Test Tenants
```php
// Free plan tenant
$freeTenant = Tenant::factory()->create(['slug' => 'free-test']);

// Pro plan tenant
$proTenant = Tenant::factory()->create(['slug' => 'pro-test']);

// Enterprise tenant (no subdomain)
$enterpriseTenant1 = Tenant::factory()->create(['slug' => 'enterprise-test-1']);

// Enterprise tenant (with subdomain)
$enterpriseTenant2 = Tenant::factory()->create([
    'slug' => 'enterprise-test-2',
    'subdomain' => 'enterprise-test',
    'subdomain_enabled' => true,
]);
```

---

## Quick Test Commands

### Test Path-Based Route
```bash
curl -H "Host: example.com" http://localhost/acme/dashboard
```

### Test Subdomain Route
```bash
curl -H "Host: acme.example.com" http://localhost/dashboard
```

---

## Notes

- Always test in staging before production
- Keep existing tests running during development
- Add new tests for subdomain features
- Monitor logs during testing
- Document any issues found

