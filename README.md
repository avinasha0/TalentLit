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

5. **Start the development server**
   ```bash
   php artisan serve
   ```

6. **Build frontend assets (optional)**
   ```bash
   npm run dev
   ```

## Usage

### Public Routes
- `GET /` - Returns "HireHub is running"

### Tenant Routes
- `GET /{tenant}/dashboard` - Tenant dashboard (e.g., `/acme/dashboard`)

### API Routes
- `GET /api/{tenant}/v1/user` - Get authenticated user (requires Sanctum token)

### Default Tenant
The application comes with a seeded "Acme" tenant:
- **URL**: `/acme/dashboard`
- **Admin User**: `admin@acme.com` / `password`
- **Role**: Owner

## Testing

Run the test suite:
```bash
php artisan test
```

## Code Quality

Run code formatting:
```bash
./vendor/bin/pint
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
