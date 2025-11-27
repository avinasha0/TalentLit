# Quick Testing Guide - Approval Workflow

## ✅ Prerequisites Check

Run the test script first:
```bash
php test_approval_workflow.php
```

This will:
- Verify migrations are run
- Create test users and roles
- Create a test requisition
- Show you the test data

## 🚀 Quick Browser Test (5 minutes)

### Step 1: Login as Hiring Manager
1. Go to: `http://localhost/{your-tenant-slug}/login`
2. Login with: `hiring@test.com` / `password`
3. Navigate to: `http://localhost/{your-tenant-slug}/requisitions`
4. Find the test requisition (ID: 11 or latest)

### Step 2: Submit for Approval
Open browser console (F12) and run:
```javascript
fetch('/{your-tenant-slug}/api/requisitions/{requisition-id}/submit', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    credentials: 'same-origin'
})
.then(r => r.json())
.then(data => {
    console.log('Result:', data);
    if (data.success) {
        alert('Submitted! Check pending approvals.');
        window.location.reload();
    }
});
```

**Expected:** Success message, requisition status changes to "Pending"

### Step 3: Login as Approver
1. Logout
2. Login with: `approver1@test.com` / `password`
3. Navigate to: `http://localhost/{your-tenant-slug}/requisitions/pending-approvals`

**Expected:** See the requisition in the list

### Step 4: Approve the Requisition
1. Click "View" on the requisition
2. Click "Approve" button
3. Confirm in alert

**Expected:** 
- Success message
- If multi-level: moves to next approver
- If final level: requisition approved
- Email sent (check logs)

### Step 5: Verify Results
Check database:
```sql
-- Check requisition status
SELECT id, job_title, approval_status, approval_level, current_approver_id 
FROM requisitions 
WHERE id = {requisition-id};

-- Check audit log
SELECT * FROM requisition_approvals 
WHERE requisition_id = {requisition-id} 
ORDER BY created_at;

-- Check tasks
SELECT * FROM tasks 
WHERE requisition_id = {requisition-id};
```

## 🧪 Test All Actions

### Test Reject
1. Create new requisition and submit
2. As approver, click "Reject"
3. Enter comments (min 10 chars)
4. Verify: Status = Rejected, email sent, task completed

### Test Request Changes
1. Create new requisition and submit
2. As approver, click "Request Changes"
3. Enter comments (min 10 chars)
4. Verify: Status = ChangesRequested, edit task created, email sent

### Test Delegate
1. Create new requisition and submit
2. As approver, open console and run:
```javascript
fetch('/{tenant}/api/requisitions/{id}/delegate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        delegate_to_user_id: {approver2-user-id}, // Get from test script output
        comments: 'Delegating to HR Manager'
    })
})
.then(r => r.json())
.then(console.log);
```
3. Verify: Current approver changed, new task created, email sent

## 📧 Test Email Notifications

Check if emails are being sent:
```bash
# If using log driver
tail -f storage/logs/laravel.log | grep "Mail"

# Or check mail config
php artisan tinker
>>> config('mail.default')
```

## 🔍 Debugging Tips

### Issue: "No approver found"
```sql
-- Check roles
SELECT u.email, cur.role_name 
FROM users u
JOIN custom_user_roles cur ON u.id = cur.user_id
WHERE cur.tenant_id = '{tenant-id}';
```

### Issue: Routes not working
```bash
php artisan route:list --name=approval
php artisan route:list --name=requisitions
```

### Issue: Permissions
```sql
-- Check user permissions
SELECT u.email, cur.role_name, tu.tenant_id
FROM users u
JOIN tenant_user tu ON u.id = tu.user_id
LEFT JOIN custom_user_roles cur ON u.id = cur.user_id AND tu.tenant_id = cur.tenant_id
WHERE tu.tenant_id = '{tenant-id}';
```

## ✅ Verification Checklist

After testing, verify:
- [ ] Requisition created with Draft status
- [ ] Submit endpoint works
- [ ] Pending approvals page shows requisition
- [ ] Approval detail page loads correctly
- [ ] Approve action works
- [ ] Reject action works (requires comments)
- [ ] Request Changes works (requires comments)
- [ ] Delegate action works
- [ ] Approval history shows all actions
- [ ] Tasks created and completed
- [ ] Email notifications sent
- [ ] Multi-level approval flows correctly
- [ ] Security prevents unauthorized access

## 🎯 Quick API Test Commands

Using curl (replace placeholders):
```bash
# Submit for approval
curl -X POST "http://localhost/{tenant}/api/requisitions/{id}/submit" \
  -H "Cookie: {session-cookie}" \
  -H "X-CSRF-TOKEN: {csrf-token}"

# Get pending approvals
curl "http://localhost/{tenant}/api/approvals/pending" \
  -H "Cookie: {session-cookie}"

# Approve
curl -X POST "http://localhost/{tenant}/api/requisitions/{id}/approve" \
  -H "Content-Type: application/json" \
  -H "Cookie: {session-cookie}" \
  -H "X-CSRF-TOKEN: {csrf-token}" \
  -d '{"comments": "Approved!"}'

# Get approval history
curl "http://localhost/{tenant}/api/requisitions/{id}/approvals" \
  -H "Cookie: {session-cookie}"
```

## 📝 Notes

- All test users have password: `password`
- Test requisition ID will be shown in test script output
- Check `storage/logs/laravel.log` for detailed error messages
- Use browser DevTools Network tab to see API responses
- Check database directly to verify data changes

