#!/bin/bash

# Quick Production Seeding Script for Departments and Locations
# Run this script on your production server

echo "🚀 HireHub Production Seeding Script"
echo "===================================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from your Laravel project root directory"
    echo "   (where artisan file is located)"
    exit 1
fi

# Check if we're in production
ENVIRONMENT=$(php artisan env)
echo "🌍 Environment: $ENVIRONMENT"
echo ""

# Create backup
echo "1️⃣ Creating database backup..."
BACKUP_FILE="backup_before_seeding_$(date +%Y%m%d_%H%M%S).sql"
php artisan db:backup --destination=local --destinationPath=$BACKUP_FILE
echo "✅ Backup created: $BACKUP_FILE"
echo ""

# Run the seeder
echo "2️⃣ Seeding Global Departments and Locations..."
php artisan db:seed --class=GlobalDepartmentLocationSeeder --force
echo ""

# Verify the seeding
echo "3️⃣ Verifying seeded data..."
php artisan tinker --execute="
echo 'Departments: ' . App\Models\GlobalDepartment::where('is_active', true)->count();
echo 'Locations: ' . App\Models\GlobalLocation::where('is_active', true)->count();
"
echo ""

echo "✅ Production seeding completed successfully!"
echo "📊 Your HireHub application now has:"
echo "   - 20+ Global Departments"
echo "   - 30+ Global Locations"
echo "   - Backup saved as: $BACKUP_FILE"
echo ""
echo "🎉 Ready to use in production!"
