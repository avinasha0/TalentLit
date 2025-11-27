# Testing Guide: Requisition Approval Workflow

## Prerequisites

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Ensure you have:**
   - At least 2 users in the system
   - Users assigned to tenant with roles (Owner, Admin, DepartmentHead, HRManager, etc.)
   - A tenant configured

## Step-by-Step Testing

### 1. Setup Test Users and Roles

First, ensure you have users with appropriate roles:

```bash
php artisan tinker
```

```php
// Get or create a tenant
$tenant = \App\Models\Tenant::firstOrCreate(['slug' => 'test-company'], ['name' => 'Test Company']);

// Create test users
$hiringManager = \App\Models\User::firstOrCreate(
    ['email' => 'hiring@test.com'],
    ['name' => 'Hiring Manager', 'password' => bcrypt('password')]
);

$approver1 = \App\Models\User::firstOrCreate(
    ['email' => 'approver1@test.com'],
    ['name' => 'Department Head', 'password' => bcrypt('password')]
);

$approver2 = \App\Models\User::firstOrCreate(
    ['email' => 'approver2@test.com'],
    ['name' => 'HR Manager', 'password' => bcrypt('password')]
);

// Assign users to tenant
$hiringManager->tenants()->syncWithoutDetaching([$tenant->id]);
$approver1->tenants()->syncWithoutDetaching([$tenant->id]);
$approver2->tenants()->syncWithoutDetaching([$tenant->id]);

// Assign roles
\DB::table('custom_user_roles')->insertOrIgnore([
    ['user_id' => $hiringManager->id, 'tenant_id' => $tenant->id, 'role_name' => 'Recruiter', 'created_at' => now(), 'updated_at' => now()],
    ['user_id' => $approver1->id, 'tenant_id' => $tenant->id, 'role_name' => 'DepartmentHead', 'created_at' => now(), 'updated_at' => now()],
    ['user_id' => $approver2->id, 'tenant_id' => $tenant->id, 'role_name' => 'HRManager', 'created_at' => now(), 'updated_at' => now()],
]);
```

### 2. Test Creating a Requisition

1. **Login as Hiring Manager**
   - Go to: `http://localhost/{tenant-slug}/requisitions/create`
   - Fill in the form and submit
   - Verify requisition is created with `status = 'Draft'` and `approval_status = 'Draft'`

2. **Check Database**
   ```sql
   SELECT id, job_title, status, approval_status, approval_level, current_approver_id 
   FROM requisitions 
   ORDER BY id DESC LIMIT 1;
   ```

### 3. Test Submitting for Approval

**Option A: Via API (Recommended for testing)**

```bash
# Get your CSRF token first by visiting the site
# Then use it in the request

curl -X POST "http://localhost/{tenant-slug}/api/requisitions/{requisition-id}/submit" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {your-csrf-token}" \
  -H "Cookie: {your-session-cookie}" \
  -d '{}'
```

**Option B: Via Browser Console**

1. Login as Hiring Manager
2. Go to requisition detail page
3. Open browser console and run:
   ```javascript
   fetch('/{tenant-slug}/api/requisitions/{requisition-id}/submit', {
       method: 'POST',
       headers: {
           'Content-Type': 'application/json',
           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
       },
       credentials: 'same-origin'
   })
   .then(r => r.json())
   .then(console.log);
   ```

**Expected Results:**
- `approval_status` changes to `'Pending'`
- `approval_level` set to `1`
- `current_approver_id` set to first approver's ID
- Task created for approver
- Email sent to approver
- Audit log entry created

**Verify:**
```sql
-- Check requisition
SELECT approval_status, approval_level, current_approver_id FROM requisitions WHERE id = {requisition-id};

-- Check task
SELECT * FROM tasks WHERE requisition_id = {requisition-id};

-- Check audit log
SELECT * FROM requisition_approvals WHERE requisition_id = {requisition-id};
```

### 4. Test Pending Approvals Page

1. **Login as Approver (DepartmentHead)**
2. **Navigate to:** `http://localhost/{tenant-slug}/requisitions/pending-approvals`
3. **Verify:**
   - Requisition appears in the list
   - Shows correct job title, department, budget, etc.
   - Shows approval level badge (L1)
   - "View" link works

### 5. Test Approval Detail Page

1. **Click "View" on pending requisition**
2. **Verify:**
   - Full requisition details displayed
   - Action buttons visible (Approve, Reject, Request Changes, Delegate)
   - Approval history timeline shows "Pending" entry
   - Summary sidebar shows current approver

### 6. Test Approve Action

**Via Browser:**
1. Click "Approve" button
2. Confirm in alert
3. Verify success message

**Via API:**
```bash
curl -X POST "http://localhost/{tenant-slug}/api/requisitions/{requisition-id}/approve" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {token}" \
  -H "Cookie: {cookie}" \
  -d '{"comments": "Looks good!"}'
```

**Expected Results:**
- If more levels exist: moves to next approver
- If last level: `approval_status = 'Approved'`, `approved_at` set
- Task marked as completed
- Email sent to requester (if final approval)
- Audit log entry created

### 7. Test Reject Action

1. **Click "Reject" button**
2. **Enter comments** (minimum 10 characters)
3. **Confirm rejection**

**Expected Results:**
- `approval_status = 'Rejected'`
- `current_approver_id = NULL`
- Task marked as completed
- Email sent to requester with comments
- Audit log entry created

### 8. Test Request Changes Action

1. **Click "Request Changes" button**
2. **Enter comments** (minimum 10 characters)
3. **Submit**

**Expected Results:**
- `approval_status = 'ChangesRequested'`
- `current_approver_id = NULL`
- Task marked as completed
- New task created for requester to edit
- Email sent to requester with comments
- Audit log entry created

### 9. Test Delegate Action

**Via Browser Console:**
```javascript
fetch('/{tenant-slug}/api/requisitions/{requisition-id}/delegate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        delegate_to_user_id: {delegate-user-id},
        comments: 'Delegating to you'
    })
})
.then(r => r.json())
.then(console.log);
```

**Expected Results:**
- `current_approver_id` changes to delegate user
- Current task marked as completed
- New task created for delegate
- Email sent to delegate
- Audit log entry created with `delegate_to` field

### 10. Test Approval History

**Via API:**
```bash
curl "http://localhost/{tenant-slug}/api/requisitions/{requisition-id}/approvals" \
  -H "Cookie: {cookie}"
```

**Expected Results:**
- Returns JSON array of all approval actions
- Shows approver, action, comments, timestamp
- Ordered chronologically

### 11. Test Multi-Level Approval

1. **Create requisition with high budget (>500000) or high priority**
2. **Submit for approval**
3. **Approve at Level 1** → Should move to Level 2 (HR Manager)
4. **Approve at Level 2** → Should move to Level 3 (Finance) if budget > 500000
5. **Continue until final approval**

**Verify workflow:**
```sql
SELECT approval_level, current_approver_id, approval_status 
FROM requisitions 
WHERE id = {requisition-id};
```

### 12. Test Security

1. **Unauthorized Approval Attempt:**
   - Login as user who is NOT the current approver
   - Try to approve → Should get 403 error

2. **Concurrent Approval:**
   - Open approval page in two browser tabs
   - Approve in first tab
   - Try to approve in second tab → Should get 409 conflict

3. **Permission Checks:**
   - User without `view_jobs` permission → Should not see pending approvals
   - User without `edit_jobs` permission → Should not be able to approve

### 13. Test Email Notifications

**Check Mail Logs:**
```bash
# If using log driver (config/mail.php)
tail -f storage/logs/laravel.log | grep "Mail"

# Or check mail queue
php artisan queue:work
```

**Verify emails are sent for:**
- Approval request (on submit)
- Final approval (on last level approval)
- Rejection (on reject)
- Changes requested (on request changes)
- Delegation (on delegate)

### 14. Test Task Integration

**Check Tasks Table:**
```sql
SELECT * FROM tasks 
WHERE requisition_id = {requisition-id} 
ORDER BY created_at DESC;
```

**Verify:**
- Task created when requisition submitted
- Task status = 'Completed' after approval action
- Edit task created when changes requested
- Delegate task created when delegated

### 15. Test Edge Cases

1. **Resubmit After Changes:**
   - Request changes on a requisition
   - Edit the requisition
   - Submit again → Should reset to Level 1

2. **No Approver Found:**
   - Submit requisition when no approver role exists
   - Should return error message

3. **Empty Workflow:**
   - Submit requisition that doesn't match any workflow conditions
   - Should still create basic workflow

## Quick Test Script

Create a test file: `test_approval_workflow.php`

```php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Requisition;
use App\Models\User;
use App\Models\Tenant;
use App\Services\ApprovalWorkflowService;

// Get tenant
$tenant = Tenant::first();
if (!$tenant) {
    echo "No tenant found. Please create one first.\n";
    exit(1);
}

// Get or create test users
$hiringManager = User::firstOrCreate(['email' => 'hiring@test.com'], [
    'name' => 'Hiring Manager',
    'password' => bcrypt('password')
]);

$approver = User::firstOrCreate(['email' => 'approver@test.com'], [
    'name' => 'Approver',
    'password' => bcrypt('password')
]);

// Assign to tenant
$hiringManager->tenants()->syncWithoutDetaching([$tenant->id]);
$approver->tenants()->syncWithoutDetaching([$tenant->id]);

// Assign roles
\DB::table('custom_user_roles')->updateOrInsert(
    ['user_id' => $approver->id, 'tenant_id' => $tenant->id],
    ['role_name' => 'DepartmentHead', 'created_at' => now(), 'updated_at' => now()]
);

// Create test requisition
$requisition = Requisition::create([
    'tenant_id' => $tenant->id,
    'department' => 'Engineering',
    'job_title' => 'Senior Software Engineer',
    'justification' => 'Need to expand the team',
    'budget_min' => 100000,
    'budget_max' => 150000,
    'contract_type' => 'Full-time',
    'skills' => json_encode(['PHP', 'Laravel', 'MySQL']),
    'experience_min' => 5,
    'experience_max' => 10,
    'headcount' => 1,
    'priority' => 'Medium',
    'location' => 'Remote',
    'status' => 'Draft',
    'approval_status' => 'Draft',
    'approval_level' => 0,
    'created_by' => $hiringManager->id,
]);

echo "✓ Requisition created: ID {$requisition->id}\n";

// Test workflow service
$workflowService = new ApprovalWorkflowService();
$workflow = $workflowService->evaluateWorkflow($requisition);
echo "✓ Workflow evaluated: " . count($workflow) . " levels\n";

$firstApprover = $workflowService->getFirstApprover($requisition);
echo "✓ First approver: " . ($firstApprover ? "User ID {$firstApprover}" : "Not found") . "\n";

echo "\nTest completed! Requisition ID: {$requisition->id}\n";
echo "You can now test the approval workflow in the UI.\n";
```

Run it:
```bash
php test_approval_workflow.php
```

## Common Issues & Solutions

### Issue: "No approver found"
**Solution:** Ensure users have roles assigned in `custom_user_roles` table with role names matching workflow (DepartmentHead, HRManager, Finance, CEO)

### Issue: "Unauthorized" errors
**Solution:** Check user has correct permissions and belongs to the tenant

### Issue: Emails not sending
**Solution:** 
- Check `.env` mail configuration
- Check `config/mail.php`
- Use `php artisan queue:work` if using queue
- Check `storage/logs/laravel.log` for errors

### Issue: Tasks not appearing
**Solution:** 
- Check `tasks` table has entries
- Verify user_id matches logged-in user
- Check task status is 'Pending'

## Verification Checklist

- [ ] Migrations run successfully
- [ ] Requisition created with Draft status
- [ ] Submit for approval works
- [ ] Pending approvals page shows requisition
- [ ] Approval detail page loads
- [ ] Approve action works
- [ ] Reject action works (with comments)
- [ ] Request changes works (with comments)
- [ ] Delegate action works
- [ ] Approval history shows all actions
- [ ] Tasks created and completed correctly
- [ ] Email notifications sent
- [ ] Multi-level approval works
- [ ] Security checks prevent unauthorized access
- [ ] Concurrent approval protection works

## Next Steps

After testing, you can:
1. Add unit tests for each endpoint
2. Add integration tests for full workflow
3. Customize workflow rules in `ApprovalWorkflowService`
4. Add dashboard widget for pending approvals count
5. Enhance UI with better modals for actions

