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
- **Analytics Dashboard**: Comprehensive ATS metrics with interactive charts and data export
- **Careers Customization**: Tenant-level branding and custom application questions for public careers pages

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
- `GET /{tenant}/analytics` - Analytics dashboard with interactive charts
- `GET /{tenant}/analytics/data` - JSON API for analytics data (supports date filtering)
- `GET /{tenant}/analytics/export` - CSV export of analytics data

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

## Analytics Dashboard

The analytics dashboard provides comprehensive insights into your hiring process with interactive charts and metrics.

### Features
- **Interactive Charts**: Built with Chart.js for responsive, interactive visualizations
- **Date Range Filtering**: Filter data by custom date ranges or use presets (30D, 90D, YTD, ALL)
- **Real-time KPIs**: Key performance indicators including total applications, hires, time-to-hire, and active pipeline
- **Data Export**: CSV export functionality for further analysis
- **Performance Optimized**: Cached queries and database indexes for fast loading on shared hosting

### Available Metrics
1. **Applications Over Time**: Line chart showing application trends
2. **Hires Over Time**: Area chart displaying hiring patterns
3. **Stage Funnel**: Horizontal bar chart showing application distribution across stages
4. **Source Effectiveness**: Grouped bar chart comparing applications vs hires by source
5. **Open Jobs by Department**: Bar chart showing job distribution
6. **Pipeline Snapshot**: Doughnut chart of current pipeline status

### Access Control
- **Roles**: Owner, Admin, Recruiter, and Hiring Manager can access analytics
- **Permission**: Requires `view analytics` permission
- **Tenant Scoped**: All data is automatically filtered by tenant

### Performance Features
- **Caching**: 60-second cache for analytics data to reduce database load
- **Database Indexes**: Optimized indexes for fast query performance
- **Efficient Queries**: Uses raw SQL with proper grouping for MySQL compatibility
- **Responsive Design**: Mobile-friendly interface with Tailwind CSS

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

## Careers Customization

The application includes comprehensive tenant-level customization for public careers pages:

### Branding Features
- **Company Logo**: Upload and display custom logos on careers pages
- **Hero Background Image**: Custom background images for the careers landing page
- **Primary Color**: Brand color customization with CSS variables
- **Intro Content**: Customizable headline and subtitle for the hero section

### Custom Application Questions
- **Question Library**: Create reusable questions that can be attached to any job
- **Question Types**: Support for short text, long text, email, phone, select, multi-select, checkbox, and file upload
- **Job-Specific Questions**: Attach custom questions to specific jobs with ordering and required status
- **Dynamic Validation**: Server-side validation for all custom question types

### Admin Interface
- **Careers Settings**: Manage branding and hero content at `/{tenant}/settings/careers`
- **Job Questions**: Configure custom questions for each job at `/{tenant}/jobs/{job}/questions`
- **Drag-and-Drop**: Easy reordering of questions with visual interface

### Public Pages
- **Branded Hero Section**: Customizable hero with logo, background, and content
- **Dynamic Application Forms**: Job-specific questions rendered based on configuration
- **Responsive Design**: Mobile-friendly forms with proper validation

### Database Structure
- `tenant_branding`: Stores branding information per tenant
- `application_questions`: Question library with tenant scoping
- `job_application_question`: Pivot table for job-question relationships
- `application_answers`: Stores candidate responses to custom questions

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
