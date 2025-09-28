# HireHub

A Laravel 11 application with path-based tenancy for hiring and recruitment management.

## Features

- **Path-based Tenancy**: Multi-tenant architecture using URL slugs (e.g., `/acme/dashboard`)
- **Laravel 11**: Latest Laravel framework with modern features
- **Livewire 3**: Dynamic frontend components
- **Tailwind CSS**: Utility-first CSS framework
- **Database Queue**: Background job processing
- **Role-based Permissions**: Using Spatie Laravel Permission
- **API Support**: RESTful API with Sanctum authentication
- **Testing**: Comprehensive test suite with PHPUnit
- **CI/CD**: GitHub Actions workflow for automated testing
- **Core Domains**: Complete hiring pipeline with candidates, jobs, applications, and interviews

## Tech Stack

- PHP 8.3+
- Laravel 11
- Livewire 3
- Tailwind CSS
- MySQL/SQLite
- Spatie Laravel Permission
- Laravel Sanctum
- Laravel Pennant
- Laravel Telescope (dev only)

## Local Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hirehub2
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Configure your database in .env file
   php artisan migrate
   php artisan db:seed
   ```

5. **Seed demo data (optional)**
   ```bash
   php artisan db:seed --class=CoreDataSeeder
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Build frontend assets (optional)**
   ```bash
   npm run dev
   ```

## Usage

### Public Routes
- `GET /` - Returns "HireHub is running"

### Tenant Routes
- `GET /{tenant}/dashboard` - Tenant dashboard (e.g., `/acme/dashboard`)
- `GET /{tenant}/jobs` - List published job openings (JSON)
- `GET /{tenant}/candidates` - List candidates with search filters (JSON)
- `POST /{tenant}/applications` - Create new application

### API Routes
- `GET /api/{tenant}/v1/user` - Get authenticated user (requires Sanctum token)

### Default Tenant
The application comes with a seeded "Acme" tenant:
- **URL**: `/acme/dashboard`
- **Admin User**: `admin@acme.com` / `password`
- **Role**: Owner

## Database Schema (ERD)

The application includes a comprehensive hiring pipeline with the following core domains:

### Core Tables
- **tenants** - Multi-tenant organization data
- **departments** - Organization departments (Engineering, Sales, HR)
- **locations** - Job locations (Chennai, Bengaluru, etc.)
- **job_requisitions** - Job requisition requests
- **job_openings** - Published job positions
- **job_stages** - Hiring pipeline stages (Sourced, Screen, Interview, Offer, Hired, Rejected)

### Candidate Management
- **candidates** - Candidate profiles with contact info and resume data
- **candidate_contacts** - Additional contact methods (email, phone)
- **tags** - Categorization tags (PHP, Laravel, Immediate Joiner, etc.)
- **candidate_tags** - Many-to-many relationship between candidates and tags
- **resumes** - File attachments for candidate resumes

### Application Pipeline
- **applications** - Job applications linking candidates to job openings
- **application_stage_events** - Audit trail of stage movements
- **application_notes** - User notes on applications
- **activities** - Tasks, calls, emails, and events related to applications
- **interviews** - Scheduled interview sessions
- **interview_feedback** - Interviewer feedback and ratings

### Compliance & Privacy
- **consents** - Privacy and marketing consent tracking
- **privacy_events** - GDPR compliance events (erase, anonymize, export)
- **attachments** - Polymorphic file attachments for various entities

### Key Features
- **Tenant Scoping**: All tables include `tenant_id` for multi-tenancy
- **UUID Primary Keys**: All tables use UUID primary keys
- **Soft Deletes**: Candidates, job openings, and applications support soft deletion
- **Audit Trail**: Complete tracking of application stage changes
- **Search & Filtering**: Full-text search capabilities for candidates and applications

## Commands

### Database Management
```bash
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Seed only core data
php artisan db:seed --class=CoreDataSeeder

# Seed only tenant data
php artisan db:seed --class=TenantSeeder
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ApplicationsFlowTest.php

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Run code formatting
./vendor/bin/pint

# Run static analysis
./vendor/bin/phpstan analyse
```

### Development
```bash
# Start development server
php artisan serve

# Start queue worker
php artisan queue:work

# Clear all caches
php artisan optimize:clear

# Generate model with factory and policy
php artisan make:model ModelName -mf --policy
```

## Contributing

1. Create a feature branch
2. Make your changes
3. Run tests and ensure they pass
4. Push to your branch
5. Open a Pull Request on GitHub
6. Wait for CI to pass
7. Squash merge when approved

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
