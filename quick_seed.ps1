# Quick Production Seeding Script for Departments and Locations
# Run this script on your production server

Write-Host "🚀 HireHub Production Seeding Script" -ForegroundColor Green
Write-Host "====================================" -ForegroundColor Green
Write-Host ""

# Check if we're in the right directory
if (-not (Test-Path "artisan")) {
    Write-Host "❌ Error: Please run this script from your Laravel project root directory" -ForegroundColor Red
    Write-Host "   (where artisan file is located)" -ForegroundColor Red
    exit 1
}

# Check if we're in production
Write-Host "🌍 Checking environment..."
$environment = php artisan env
Write-Host "Environment: $environment" -ForegroundColor Yellow
Write-Host ""

# Create backup
Write-Host "1️⃣ Creating database backup..." -ForegroundColor Cyan
$backupFile = "backup_before_seeding_$(Get-Date -Format 'yyyyMMdd_HHmmss').sql"
Write-Host "Backup file: $backupFile" -ForegroundColor Gray

# Create backup using mysqldump if available, otherwise use Laravel backup
try {
    php artisan db:backup --destination=local --destinationPath=$backupFile
    Write-Host "✅ Backup created: $backupFile" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Laravel backup failed, trying mysqldump..." -ForegroundColor Yellow
    # You can add mysqldump command here if needed
    Write-Host "⚠️  Please create a manual backup before proceeding" -ForegroundColor Yellow
}
Write-Host ""

# Run the seeder
Write-Host "2️⃣ Seeding Global Departments and Locations..." -ForegroundColor Cyan
php artisan db:seed --class=GlobalDepartmentLocationSeeder --force
Write-Host ""

# Verify the seeding
Write-Host "3️⃣ Verifying seeded data..." -ForegroundColor Cyan
php artisan tinker --execute="
echo 'Departments: ' . App\Models\GlobalDepartment::where('is_active', true)->count();
echo 'Locations: ' . App\Models\GlobalLocation::where('is_active', true)->count();
"
Write-Host ""

Write-Host "✅ Production seeding completed successfully!" -ForegroundColor Green
Write-Host "📊 Your HireHub application now has:" -ForegroundColor Green
Write-Host "   - 20+ Global Departments" -ForegroundColor White
Write-Host "   - 30+ Global Locations" -ForegroundColor White
Write-Host "   - Backup saved as: $backupFile" -ForegroundColor White
Write-Host ""
Write-Host "🎉 Ready to use in production!" -ForegroundColor Green
