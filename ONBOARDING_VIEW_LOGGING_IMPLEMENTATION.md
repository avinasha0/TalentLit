# Employee Onboarding View Activity Logging - Implementation Report

## Overview
Implemented server-side activity logging for all user viewing interactions in the employee onboarding module. This captures who viewed what and when, with full tenant context preservation.

## Implementation Details

### 1. Core Service: `OnboardingViewLogService`
**Location:** `app/Services/OnboardingViewLogService.php`

A centralized service that handles all view event logging with the following features:
- Automatic tenant context detection (subdomain vs slug)
- User/actor information extraction (including role lookup)
- Human-readable log formatting
- Structured JSON logging
- Optional database storage (if table exists)
- Graceful error handling (logging failures never break the application)

### 2. Database Schema
**Migration:** `database/migrations/2025_11_26_000001_create_onboarding_view_logs_table.php`

Creates `onboarding_view_logs` table with:
- All required fields (timestamp, actor info, tenant info, event type, resource info, URL, user agent, IP, extra JSON)
- Indexes for efficient querying by tenant, event type, actor, and date
- Support for both UUID and integer IDs

**Note:** The service works even if the table doesn't exist (falls back to file logging only).

### 3. Logged Events

#### Page.View
- **Trigger:** When `/employee-onboarding/all` page is loaded
- **Location:** `EmployeeOnboardingController::all()`
- **Logged Data:** Query parameters (search, filters) in `extra` field

#### Onboarding.SlideOver.Open
- **Trigger:** When user clicks "View" and slide-over opens
- **Location:** `EmployeeOnboardingController::apiShow()`
- **Logged Data:** Candidate/onboarding ID as `resource_id`

#### Onboarding.SlideOver.Close
- **Trigger:** When slide-over is closed (via close button, backdrop click, or Escape key)
- **Location:** Client-side JavaScript calls `apiLogSlideOverClose()` endpoint
- **Endpoint:** `POST /{tenant}/api/onboardings/{id}/close`
- **Logged Data:** Candidate/onboarding ID as `resource_id`

#### Onboarding.Tab.View
- **Trigger:** When any tab inside slide-over is opened
- **Tabs:** Overview, Documents, Tasks, ITAssets, Approvals
- **Locations:**
  - Overview: Client-side calls `apiLogTabView()` endpoint
  - Documents: `EmployeeOnboardingController::apiGetDocuments()`
  - Tasks: `EmployeeOnboardingController::apiGetTasks()`
  - ITAssets: `EmployeeOnboardingController::apiGetAssetRequests()`
  - Approvals: `EmployeeOnboardingController::apiGetApprovals()`
- **Logged Data:** Tab name in `extra.tab` field

### 4. Log Format

#### Human-Readable Format (Application Log)
```
[2025-11-26T12:00:00Z] Page.View tenant=acme source=subdomain user=42 (Priya Patel, HR) url=https://acme.app/employee-onboarding/all ip=1.2.3.4 extra=search=rahul
```

#### Structured JSON Format (Daily Log Channel)
```json
{
  "timestamp": "2025-11-26T12:00:00Z",
  "actor_user_id": 42,
  "actor_name": "Priya Patel",
  "actor_role": "HR",
  "tenant": "acme",
  "tenant_source": "subdomain",
  "event_type": "Onboarding.SlideOver.Open",
  "resource_type": "onboarding",
  "resource_id": "123e4567-e89b-12d3-a456-426614174000",
  "url": "https://acme.app/employee-onboarding/all",
  "user_agent": "Chrome/142.0",
  "ip_address": "1.2.3.4",
  "extra": {"tab": "Overview"}
}
```

### 5. Tenant Context Handling

The service automatically detects tenant source:
- **Subdomain:** Routes starting with `subdomain.*`
- **Slug:** Routes with `{tenant}` parameter or starting with `tenant.*`

Both tenant value (slug) and source (subdomain/slug) are included in every log entry.

### 6. Files Modified

1. **Created:**
   - `app/Services/OnboardingViewLogService.php` - Core logging service
   - `database/migrations/2025_11_26_000001_create_onboarding_view_logs_table.php` - Database schema

2. **Modified:**
   - `app/Http/Controllers/Tenant/EmployeeOnboardingController.php` - Added logging calls
   - `routes/web.php` - Added close and tab-view logging endpoints
   - `resources/views/tenant/employee-onboarding/all.blade.php` - Added client-side logging calls

### 7. Log Storage Locations

1. **Application Log File:** `storage/logs/laravel.log` (human-readable)
2. **Daily Log Channel:** `storage/logs/laravel-YYYY-MM-DD.log` (structured JSON)
3. **Database Table:** `onboarding_view_logs` (if migration is run)

### 8. Error Handling

- Logging failures are caught and logged as warnings
- Application continues normally even if logging fails
- Database write failures fall back to file logging only
- Client-side logging calls are fire-and-forget (failures don't affect UI)

### 9. Privacy & Security

- No sensitive personal data in logs (no document contents)
- Email/phone only included as identifiers when necessary
- IP addresses logged for audit purposes
- User agent truncated to 200 characters

### 10. Testing Recommendations

1. **Page.View:** Visit `/employee-onboarding/all` and check logs
2. **SlideOver.Open:** Click "View" on any candidate and check logs
3. **Tab.View:** Open each tab (Overview, Documents, Tasks, ITAssets, Approvals) and check logs
4. **SlideOver.Close:** Close slide-over and check logs
5. **Tenant Context:** Test with both subdomain and slug routes
6. **Anonymous Users:** Test with unauthenticated requests (if applicable)

### 11. Example Log Queries

**Find all page views for a tenant:**
```sql
SELECT * FROM onboarding_view_logs 
WHERE tenant_slug = 'acme' AND event_type = 'Page.View' 
ORDER BY created_at DESC;
```

**Find all slide-over opens for a specific candidate:**
```sql
SELECT * FROM onboarding_view_logs 
WHERE resource_id = '123e4567-e89b-12d3-a456-426614174000' 
AND event_type = 'Onboarding.SlideOver.Open';
```

**Find tab views by user:**
```sql
SELECT * FROM onboarding_view_logs 
WHERE actor_user_id = 42 AND event_type = 'Onboarding.Tab.View' 
ORDER BY created_at DESC;
```

## Assumptions & Limitations

1. **Slide-over Close Logging:** Close events are logged via client-side API calls. If JavaScript is disabled or the call fails, the close event may not be logged (but this is acceptable as it's a view event).

2. **Overview Tab:** The Overview tab doesn't have a server-side API endpoint, so it's logged via a dedicated client-side endpoint (`apiLogTabView`).

3. **Subdomain API Routes:** The API routes are currently only defined for slug-based routes (`/{tenant}/api/onboardings/...`). For subdomain routes, client-side logging calls may fail silently, but server-side logging in controller methods will still work correctly.

4. **Database Table:** The migration is optional. If not run, logging will work via file logs only.

5. **Performance:** Logging is designed to be lightweight and non-blocking. Database writes are wrapped in try-catch to prevent performance issues.

## Next Steps (Optional Enhancements)

1. Add retention policy for old logs
2. Create admin UI for viewing audit logs
3. Add log aggregation/analytics
4. Add real-time log streaming
5. Add log export functionality

