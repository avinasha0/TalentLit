# Employee Onboarding Module - File Structure

## Complete Directory Structure

```
hirehub2/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Tenant/
│   │   │       └── EmployeeOnboarding/          [NEW DIRECTORY]
│   │   │           ├── EmployeeOnboardingController.php
│   │   │           ├── EmployeeController.php
│   │   │           ├── OnboardingTaskController.php
│   │   │           ├── OnboardingTemplateController.php
│   │   │           └── OnboardingWorkflowController.php
│   │   │
│   │   ├── Middleware/
│   │   │   └── EmployeeOnboardingEnabled.php    [NEW FILE]
│   │   │
│   │   └── Requests/
│   │       └── EmployeeOnboarding/              [NEW DIRECTORY]
│   │           ├── StoreEmployeeRequest.php
│   │           ├── UpdateEmployeeRequest.php
│   │           ├── StoreTaskRequest.php
│   │           ├── UpdateTaskRequest.php
│   │           ├── StoreTemplateRequest.php
│   │           └── UpdateTemplateRequest.php
│   │
│   ├── Models/
│   │   └── EmployeeOnboarding/                  [NEW DIRECTORY]
│   │       ├── Employee.php
│   │       ├── OnboardingTask.php
│   │       ├── OnboardingTemplate.php
│   │       ├── OnboardingWorkflow.php
│   │       └── OnboardingChecklist.php
│   │
│   ├── Services/
│   │   └── EmployeeOnboarding/                  [NEW DIRECTORY]
│   │       ├── OnboardingService.php
│   │       ├── TaskService.php
│   │       └── NotificationService.php
│   │
│   ├── Policies/
│   │   └── EmployeeOnboarding/                  [NEW DIRECTORY]
│   │       ├── EmployeePolicy.php
│   │       ├── OnboardingTaskPolicy.php
│   │       └── OnboardingTemplatePolicy.php
│   │
│   └── Models/
│       └── Tenant.php                           [MODIFY - Add field]
│
├── database/
│   └── migrations/
│       ├── YYYY_MM_DD_HHMMSS_add_employee_onboarding_enabled_to_tenants_table.php  [NEW]
│       ├── YYYY_MM_DD_HHMMSS_create_employees_table.php                            [NEW]
│       ├── YYYY_MM_DD_HHMMSS_create_onboarding_tasks_table.php                      [NEW]
│       ├── YYYY_MM_DD_HHMMSS_create_onboarding_templates_table.php                 [NEW]
│       ├── YYYY_MM_DD_HHMMSS_create_onboarding_workflows_table.php                 [NEW]
│       └── YYYY_MM_DD_HHMMSS_create_onboarding_checklists_table.php                [NEW]
│
├── resources/
│   └── views/
│       └── tenant/
│           └── employee-onboarding/             [EXISTING - EXPAND]
│               ├── index.blade.php               [MODIFY]
│               ├── employees/                    [NEW DIRECTORY]
│               │   ├── index.blade.php
│               │   ├── create.blade.php
│               │   ├── edit.blade.php
│               │   └── show.blade.php
│               ├── tasks/                        [NEW DIRECTORY]
│               │   ├── index.blade.php
│               │   ├── create.blade.php
│               │   └── edit.blade.php
│               ├── templates/                    [NEW DIRECTORY]
│               │   ├── index.blade.php
│               │   ├── create.blade.php
│               │   └── edit.blade.php
│               └── workflows/                   [NEW DIRECTORY]
│                   ├── index.blade.php
│                   ├── create.blade.php
│                   └── edit.blade.php
│
├── routes/
│   └── web.php                                   [MODIFY - Add routes & middleware]
│
├── bootstrap/
│   └── app.php                                   [MODIFY - Register middleware]
│
├── config/
│   └── employee-onboarding.php                  [NEW FILE - Optional]
│
├── resources/
│   └── views/
│       ├── components/
│       │   └── sidebar.blade.php                [MODIFY - Conditional display]
│       ├── layouts/
│       │   └── partials/
│       │       └── mobile-menu.blade.php        [MODIFY - Conditional display]
│       └── tenant/
│           └── settings/
│               └── general.blade.php            [MODIFY - Add toggle]
│
└── app/
    └── Http/
        └── Controllers/
            └── Tenant/
                └── GeneralSettingsController.php [MODIFY - Add field handling]
```

## Key Files to Modify

### 1. Database Migration
**File**: `database/migrations/YYYY_MM_DD_HHMMSS_add_employee_onboarding_enabled_to_tenants_table.php`

```php
Schema::table('tenants', function (Blueprint $table) {
    $table->boolean('employee_onboarding_enabled')->default(false)->after('careers_enabled');
});
```

### 2. Tenant Model
**File**: `app/Models/Tenant.php`

```php
protected $fillable = [
    // ... existing fields
    'employee_onboarding_enabled',
];

public function isEmployeeOnboardingEnabled(): bool
{
    return $this->employee_onboarding_enabled ?? false;
}
```

### 3. Middleware
**File**: `app/Http/Middleware/EmployeeOnboardingEnabled.php`

```php
public function handle(Request $request, Closure $next)
{
    $tenant = tenant();
    
    if (!$tenant || !$tenant->isEmployeeOnboardingEnabled()) {
        return redirect()
            ->route('tenant.dashboard', $tenant->slug ?? tenant()->slug)
            ->with('error', 'Employee Onboarding module is not enabled for your organization.');
    }
    
    return $next($request);
}
```

### 4. Routes
**File**: `routes/web.php`

```php
// Path-based routes
Route::middleware(['capture.tenant', 'tenant', 'auth', 'employee.onboarding.enabled'])
    ->prefix('{tenant}/employee-onboarding')
    ->name('tenant.employee-onboarding.')
    ->group(function () {
        Route::get('/', [EmployeeOnboardingController::class, 'index'])->name('index');
        // ... other routes
    });

// Subdomain routes
Route::domain('{subdomain}.' . $appDomain)
    ->middleware(['subdomain.redirect', 'subdomain.tenant', 'auth', 'employee.onboarding.enabled'])
    ->prefix('employee-onboarding')
    ->name('subdomain.employee-onboarding.')
    ->group(function () {
        Route::get('/', [EmployeeOnboardingController::class, 'index'])->name('index');
        // ... other routes
    });
```

### 5. Sidebar Navigation
**File**: `resources/views/components/sidebar.blade.php`

```php
@if($tenant->employee_onboarding_enabled)
    <a href="{{ tenantRoute('tenant.employee-onboarding.index', $tenant->slug) }}"
       class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $isEmployeeOnboarding ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            Employee Onboarding
        </div>
    </a>
@endif
```

### 6. Settings Toggle
**File**: `resources/views/tenant/settings/general.blade.php`

```php
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <label for="employee_onboarding_enabled" class="block text-sm font-medium text-gray-900">
                Employee Onboarding Module
            </label>
            <p class="mt-1 text-sm text-gray-500">
                Enable the Employee Onboarding module to manage onboarding workflows, tasks, and checklists for new employees.
            </p>
        </div>
        <input type="checkbox" 
               name="employee_onboarding_enabled" 
               id="employee_onboarding_enabled"
               value="1"
               {{ old('employee_onboarding_enabled', $tenant->employee_onboarding_enabled) ? 'checked' : '' }}
               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
    </div>
</div>
```

## File Naming Conventions

- **Controllers**: PascalCase, singular (e.g., `EmployeeController.php`)
- **Models**: PascalCase, singular (e.g., `Employee.php`)
- **Services**: PascalCase, singular with "Service" suffix (e.g., `OnboardingService.php`)
- **Policies**: PascalCase, singular with "Policy" suffix (e.g., `EmployeePolicy.php`)
- **Requests**: PascalCase, descriptive (e.g., `StoreEmployeeRequest.php`)
- **Migrations**: snake_case with timestamp prefix
- **Views**: kebab-case, matching controller actions (e.g., `index.blade.php`)

## Route Naming Convention

- Path-based: `tenant.employee-onboarding.*`
- Subdomain: `subdomain.employee-onboarding.*`
- Resource routes: `tenant.employee-onboarding.employees.*`

## Model Relationships

```
Tenant
  └── hasMany(Employee)

Employee
  ├── belongsTo(Tenant)
  ├── hasMany(OnboardingTask)
  └── belongsTo(OnboardingTemplate)

OnboardingTask
  ├── belongsTo(Employee)
  ├── belongsTo(OnboardingTemplate)
  └── belongsTo(OnboardingWorkflow)

OnboardingTemplate
  ├── belongsTo(Tenant)
  ├── hasMany(OnboardingTask)
  └── hasMany(Employee)

OnboardingWorkflow
  ├── belongsTo(Tenant)
  └── hasMany(OnboardingTask)

OnboardingChecklist
  ├── belongsTo(OnboardingTask)
  └── belongsTo(Employee)
```

