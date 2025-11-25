# Employee Onboarding Module - Implementation Plan

## Overview
This document outlines the plan to create a separate, configurable Employee Onboarding module that can be enabled/disabled per tenant. The module will be completely isolated from other modules and will only be accessible when enabled.

## Current State Analysis

### Existing Implementation
- **Controller**: `app/Http/Controllers/Tenant/EmployeeOnboardingController.php` (basic placeholder)
- **View**: `resources/views/tenant/employee-onboarding/index.blade.php` (placeholder view)
- **Routes**: Defined in `routes/web.php` (lines 404-407, 740-743)
- **Navigation**: Present in sidebar but marked as "Under Development"

### Issues with Current Implementation
1. No configuration flag to enable/disable the module
2. Routes are always accessible regardless of configuration
3. Navigation always shows the module link
4. No proper module structure or separation
5. No dedicated models, services, or business logic

## Implementation Plan

### Phase 1: Database & Configuration Setup

#### 1.1 Migration - Add Configuration Field
**File**: `database/migrations/YYYY_MM_DD_HHMMSS_add_employee_onboarding_enabled_to_tenants_table.php`

```php
- Add `employee_onboarding_enabled` boolean field to `tenants` table
- Default value: `false`
- Add to `$fillable` array in Tenant model
```

#### 1.2 Update Tenant Model
**File**: `app/Models/Tenant.php`

```php
- Add 'employee_onboarding_enabled' to $fillable array
- Add helper method: isEmployeeOnboardingEnabled(): bool
- Add to $casts array if needed
```

#### 1.3 Configuration File (Optional)
**File**: `config/employee-onboarding.php`

```php
- Module-level configuration
- Default settings
- Feature flags
- Limits and constraints
```

### Phase 2: File Structure Creation

#### 2.1 Directory Structure
```
app/
├── Http/
│   └── Controllers/
│       └── Tenant/
│           └── EmployeeOnboarding/
│               ├── EmployeeOnboardingController.php (main controller)
│               ├── EmployeeController.php (manage employees)
│               ├── OnboardingTaskController.php (manage tasks)
│               ├── OnboardingTemplateController.php (manage templates)
│               └── OnboardingWorkflowController.php (manage workflows)
│
├── Models/
│   └── EmployeeOnboarding/
│       ├── Employee.php
│       ├── OnboardingTask.php
│       ├── OnboardingTemplate.php
│       ├── OnboardingWorkflow.php
│       └── OnboardingChecklist.php
│
├── Services/
│   └── EmployeeOnboarding/
│       ├── OnboardingService.php
│       ├── TaskService.php
│       └── NotificationService.php
│
├── Requests/
│   └── EmployeeOnboarding/
│       ├── StoreEmployeeRequest.php
│       ├── UpdateEmployeeRequest.php
│       ├── StoreTaskRequest.php
│       └── StoreTemplateRequest.php
│
├── Policies/
│   └── EmployeeOnboarding/
│       ├── EmployeePolicy.php
│       └── OnboardingTaskPolicy.php
│
└── Middleware/
    └── EmployeeOnboardingEnabled.php (check if module is enabled)
```

#### 2.2 Views Structure
```
resources/views/
└── tenant/
    └── employee-onboarding/
        ├── index.blade.php (dashboard)
        ├── employees/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── tasks/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        ├── templates/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        └── workflows/
            ├── index.blade.php
            ├── create.blade.php
            └── edit.blade.php
```

### Phase 3: Middleware & Route Protection

#### 3.1 Create Middleware
**File**: `app/Http/Middleware/EmployeeOnboardingEnabled.php`

**Purpose**: Check if Employee Onboarding module is enabled for the current tenant

**Logic**:
- Get current tenant
- Check `employee_onboarding_enabled` flag
- If disabled, redirect to dashboard with message
- If enabled, allow request to proceed

#### 3.2 Register Middleware
**File**: `bootstrap/app.php`

```php
- Add middleware alias: 'employee.onboarding.enabled'
```

#### 3.3 Update Routes
**File**: `routes/web.php`

**Changes**:
- Wrap all Employee Onboarding routes with `employee.onboarding.enabled` middleware
- Apply to both path-based routes (`/{tenant}/employee-onboarding/*`)
- Apply to subdomain routes (`/employee-onboarding/*`)
- Group routes under `Route::prefix('employee-onboarding')` for better organization

**Route Structure**:
```php
// Path-based routes
Route::middleware(['capture.tenant', 'tenant', 'auth', 'employee.onboarding.enabled'])
    ->prefix('{tenant}/employee-onboarding')
    ->group(function () {
        // All employee onboarding routes
    });

// Subdomain routes
Route::domain('{subdomain}.' . $appDomain)
    ->middleware(['subdomain.redirect', 'subdomain.tenant', 'auth', 'employee.onboarding.enabled'])
    ->prefix('employee-onboarding')
    ->group(function () {
        // All employee onboarding routes
    });
```

### Phase 4: Navigation & UI Updates

#### 4.1 Update Sidebar Component
**File**: `resources/views/components/sidebar.blade.php`

**Changes**:
- Wrap Employee Onboarding menu item with conditional check
- Only show if `$tenant->employee_onboarding_enabled === true`
- Remove "Under Development" badge when module is enabled

**Implementation**:
```php
@if($tenant->employee_onboarding_enabled)
    <!-- Employee Onboarding menu item -->
@endif
```

#### 4.2 Update Mobile Menu
**File**: `resources/views/layouts/partials/mobile-menu.blade.php`

**Changes**:
- Add same conditional check for mobile navigation

#### 4.3 Update Breadcrumbs
**File**: `resources/views/tenant/employee-onboarding/index.blade.php`

**Changes**:
- Ensure breadcrumbs work correctly
- Add proper navigation links

### Phase 5: Settings Integration

#### 5.1 Add to General Settings
**File**: `app/Http/Controllers/Tenant/GeneralSettingsController.php`

**Changes**:
- Add `employee_onboarding_enabled` to validation rules
- Add to update logic
- Add to view data

#### 5.2 Update Settings View
**File**: `resources/views/tenant/settings/general.blade.php`

**Changes**:
- Add toggle switch for Employee Onboarding
- Add description/help text
- Add to form submission

**UI Element**:
```html
<div class="flex items-center">
    <input type="checkbox" 
           name="employee_onboarding_enabled" 
           id="employee_onboarding_enabled"
           {{ old('employee_onboarding_enabled', $tenant->employee_onboarding_enabled) ? 'checked' : '' }}>
    <label for="employee_onboarding_enabled">
        Enable Employee Onboarding Module
    </label>
    <p class="text-sm text-gray-500">
        Enable this module to manage employee onboarding workflows, tasks, and checklists.
    </p>
</div>
```

### Phase 6: Models & Database Schema

#### 6.1 Create Migrations
**Files**:
- `database/migrations/YYYY_MM_DD_HHMMSS_create_employees_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_onboarding_tasks_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_onboarding_templates_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_onboarding_workflows_table.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_onboarding_checklists_table.php`

#### 6.2 Model Relationships
- Employee belongs to Tenant
- Employee has many OnboardingTasks
- OnboardingTask belongs to Employee, OnboardingTemplate, OnboardingWorkflow
- OnboardingTemplate has many OnboardingTasks
- OnboardingWorkflow has many OnboardingTasks

### Phase 7: Controllers & Business Logic

#### 7.1 Main Controller Structure
**File**: `app/Http/Controllers/Tenant/EmployeeOnboarding/EmployeeOnboardingController.php`

**Methods**:
- `index()` - Dashboard/overview
- `dashboard()` - Statistics and overview data

#### 7.2 Employee Controller
**File**: `app/Http/Controllers/Tenant/EmployeeOnboarding/EmployeeController.php`

**Methods**:
- `index()` - List all employees
- `create()` - Show create form
- `store()` - Store new employee
- `show()` - View employee details
- `edit()` - Show edit form
- `update()` - Update employee
- `destroy()` - Delete employee

#### 7.3 Task Controller
**File**: `app/Http/Controllers/Tenant/EmployeeOnboarding/OnboardingTaskController.php`

**Methods**:
- `index()` - List tasks
- `create()` - Create task
- `store()` - Store task
- `update()` - Update task status
- `destroy()` - Delete task

### Phase 8: Permissions & Authorization

#### 8.1 Create Permissions
**Permissions to create**:
- `view_employee_onboarding`
- `manage_employee_onboarding`
- `create_employees`
- `edit_employees`
- `delete_employees`
- `manage_onboarding_tasks`
- `manage_onboarding_templates`

#### 8.2 Create Policies
**Files**:
- `app/Policies/EmployeeOnboarding/EmployeePolicy.php`
- `app/Policies/EmployeeOnboarding/OnboardingTaskPolicy.php`

#### 8.3 Update Routes with Permissions
- Add permission middleware to routes
- Use `custom.permission:view_employee_onboarding` middleware

### Phase 9: Services & Business Logic

#### 9.1 Onboarding Service
**File**: `app/Services/EmployeeOnboarding/OnboardingService.php`

**Methods**:
- `startOnboarding(Employee $employee, OnboardingTemplate $template)`
- `completeTask(OnboardingTask $task)`
- `getOnboardingProgress(Employee $employee)`
- `sendReminder(Employee $employee)`

#### 9.2 Notification Service
**File**: `app/Services/EmployeeOnboarding/NotificationService.php`

**Methods**:
- `sendWelcomeEmail(Employee $employee)`
- `sendTaskReminder(OnboardingTask $task)`
- `sendCompletionNotification(Employee $employee)`

### Phase 10: Testing & Validation

#### 10.1 Feature Tests
**Files**:
- `tests/Feature/EmployeeOnboarding/EmployeeOnboardingTest.php`
- `tests/Feature/EmployeeOnboarding/EmployeeTest.php`
- `tests/Feature/EmployeeOnboarding/OnboardingTaskTest.php`

#### 10.2 Test Scenarios
- Module disabled: Routes should redirect
- Module enabled: Routes should work
- Navigation: Should only show when enabled
- Settings: Toggle should work correctly
- Permissions: Should be enforced

## Implementation Checklist

### Database & Configuration
- [ ] Create migration for `employee_onboarding_enabled` field
- [ ] Update Tenant model with new field
- [ ] Add helper method `isEmployeeOnboardingEnabled()`
- [ ] Create configuration file (optional)

### File Structure
- [ ] Create directory structure for controllers
- [ ] Create directory structure for models
- [ ] Create directory structure for services
- [ ] Create directory structure for requests
- [ ] Create directory structure for policies
- [ ] Create directory structure for views

### Middleware & Routes
- [ ] Create `EmployeeOnboardingEnabled` middleware
- [ ] Register middleware in `bootstrap/app.php`
- [ ] Update routes with middleware protection
- [ ] Group routes under prefix
- [ ] Update both path-based and subdomain routes

### Navigation & UI
- [ ] Update sidebar with conditional display
- [ ] Update mobile menu with conditional display
- [ ] Update breadcrumbs
- [ ] Remove "Under Development" badge

### Settings Integration
- [ ] Add toggle to General Settings controller
- [ ] Add toggle to General Settings view
- [ ] Add validation rules
- [ ] Test toggle functionality

### Models & Database
- [ ] Create Employee model and migration
- [ ] Create OnboardingTask model and migration
- [ ] Create OnboardingTemplate model and migration
- [ ] Create OnboardingWorkflow model and migration
- [ ] Create OnboardingChecklist model and migration
- [ ] Define relationships

### Controllers
- [ ] Create main EmployeeOnboardingController
- [ ] Create EmployeeController
- [ ] Create OnboardingTaskController
- [ ] Create OnboardingTemplateController
- [ ] Create OnboardingWorkflowController

### Permissions
- [ ] Create permissions in seeder
- [ ] Create policies
- [ ] Add permission checks to routes
- [ ] Test permission enforcement

### Services
- [ ] Create OnboardingService
- [ ] Create TaskService
- [ ] Create NotificationService

### Testing
- [ ] Write feature tests
- [ ] Test module enable/disable
- [ ] Test route protection
- [ ] Test navigation visibility
- [ ] Test permissions

## Configuration Flow

### Enabling the Module
1. Admin navigates to Settings → General
2. Toggles "Enable Employee Onboarding Module"
3. Saves settings
4. `employee_onboarding_enabled` is set to `true` in database
5. Navigation menu item appears
6. Routes become accessible

### Disabling the Module
1. Admin navigates to Settings → General
2. Toggles off "Enable Employee Onboarding Module"
3. Saves settings
4. `employee_onboarding_enabled` is set to `false` in database
5. Navigation menu item disappears
6. Routes redirect to dashboard with message

## Security Considerations

1. **Route Protection**: All routes must check module enablement
2. **Permission Checks**: Even if enabled, users need proper permissions
3. **Data Isolation**: All data must be tenant-scoped
4. **Middleware Order**: Check module enablement before permission checks
5. **API Protection**: If API routes are added, protect them too

## Migration Strategy

### For Existing Tenants
- Default `employee_onboarding_enabled` to `false`
- Existing tenants won't see the module until they enable it
- No data migration needed (new module)

### For New Tenants
- Default `employee_onboarding_enabled` to `false`
- Can be enabled during onboarding setup
- Can be enabled later in settings

## Future Enhancements

1. **Onboarding Templates**: Pre-built templates for different roles
2. **Automated Workflows**: Trigger tasks based on events
3. **Integration**: Connect with HR systems
4. **Analytics**: Track onboarding completion rates
5. **Document Management**: Upload and manage onboarding documents
6. **E-signatures**: Digital signature collection
7. **Calendar Integration**: Schedule onboarding sessions
8. **Mobile App**: Mobile-friendly onboarding experience

## Notes

- This plan separates Employee Onboarding as a completely independent module
- The module can be enabled/disabled per tenant
- All routes, navigation, and features respect the configuration
- The structure follows Laravel best practices
- The module is tenant-scoped and permission-protected
- The implementation is backward compatible (existing code won't break)

## Estimated Implementation Time

- **Phase 1-2**: 2-3 hours (Database & Structure)
- **Phase 3-4**: 2-3 hours (Middleware & Navigation)
- **Phase 5**: 1-2 hours (Settings Integration)
- **Phase 6-7**: 4-6 hours (Models & Controllers)
- **Phase 8-9**: 3-4 hours (Permissions & Services)
- **Phase 10**: 2-3 hours (Testing)

**Total**: 14-21 hours

## Dependencies

- Laravel 11
- Existing tenant system
- Permission system (Spatie Laravel Permission)
- Existing middleware infrastructure
- Existing navigation components

