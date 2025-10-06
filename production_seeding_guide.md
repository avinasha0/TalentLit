# Production Seeding Guide for Departments and Locations

## 🎯 Overview
This guide provides step-by-step instructions to seed department and location data in production for the HireHub application.

## 📋 Prerequisites
- Production server access
- Database credentials
- Laravel application deployed
- Backup of production database (recommended)

## 🚀 Step-by-Step Production Seeding

### Step 1: Backup Production Database
```bash
# Create backup before seeding
mysqldump -u [username] -p[password] [database_name] > backup_before_seeding_$(date +%Y%m%d_%H%M%S).sql

# Or using Laravel
php artisan db:backup
```

### Step 2: Verify Current Seeding Infrastructure
```bash
# Check if seeder exists
ls -la database/seeders/GlobalDepartmentLocationSeeder.php

# Verify the seeder is properly configured
php artisan list db:seed
```

### Step 3: Test Seeding in Staging/Development First
```bash
# Test the seeder locally first
php artisan db:seed --class=GlobalDepartmentLocationSeeder

# Verify data was created
php artisan tinker
# In tinker:
# App\Models\GlobalDepartment::count()
# App\Models\GlobalLocation::count()
```

### Step 4: Production Seeding Commands

#### Option A: Seed Only Global Departments and Locations
```bash
# Run only the global department and location seeder
php artisan db:seed --class=GlobalDepartmentLocationSeeder --force
```

#### Option B: Run All Seeders (if needed)
```bash
# Run all seeders (be careful in production)
php artisan db:seed --force
```

#### Option C: Run Specific Seeders in Order
```bash
# Run seeders one by one for better control
php artisan db:seed --class=GlobalDepartmentLocationSeeder --force
php artisan db:seed --class=TenantSeeder --force
php artisan db:seed --class=UserSeeder --force
# ... continue with other seeders as needed
```

### Step 5: Verify Seeding Results
```bash
# Check department count
php artisan tinker
# In tinker:
# App\Models\GlobalDepartment::count() // Should be 20
# App\Models\GlobalLocation::count() // Should be 30

# Check specific data
# App\Models\GlobalDepartment::where('is_active', true)->get()
# App\Models\GlobalLocation::where('is_active', true)->get()
```

### Step 6: Update DatabaseSeeder (Optional)
If you want to include the GlobalDepartmentLocationSeeder in the main seeder:

```php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    $this->call([
        // Add this line
        GlobalDepartmentLocationSeeder::class,
        
        // Existing seeders
        TenantSeeder::class,
        UserSeeder::class,
        // ... rest of seeders
    ]);
}
```

## 📊 What Gets Seeded

### Global Departments (20 departments)
- Engineering, HR, Marketing, Sales, Finance
- Operations, Customer Support, Product Management
- Quality Assurance, Business Development, Legal
- IT Support, R&D, Supply Chain, Data Analytics
- Design, Content, Security, DevOps, Strategy

### Global Locations (30 locations)
- **Indian Cities**: Mumbai, Delhi, Bangalore, Hyderabad, Chennai, Kolkata, Pune, Ahmedabad, Jaipur, Surat
- **IT Hubs**: Gurgaon, Noida, Kochi, Coimbatore, Chandigarh, Indore, Bhubaneswar, Vadodara, Nashik, Vijayawada
- **Remote Options**: Remote - India, Work from Home, Hybrid - India
- **International**: New York, San Francisco, London, Singapore, Dubai, Toronto, Sydney, Berlin, Amsterdam, Tokyo

## 🔧 Production-Specific Considerations

### Environment Variables
Ensure these are set in production:
```bash
DB_CONNECTION=mysql
DB_HOST=your_production_host
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_production_username
DB_PASSWORD=your_production_password
```

### Safety Measures
1. **Always backup before seeding**
2. **Test in staging environment first**
3. **Use `--force` flag only when necessary**
4. **Monitor database performance during seeding**
5. **Check application logs after seeding**

### Rollback Plan
If something goes wrong:
```bash
# Restore from backup
mysql -u [username] -p[password] [database_name] < backup_before_seeding_YYYYMMDD_HHMMSS.sql

# Or drop and recreate specific tables
php artisan migrate:rollback --step=1
```

## 🚨 Troubleshooting

### Common Issues
1. **Permission denied**: Ensure database user has INSERT/UPDATE permissions
2. **Memory limit**: Increase PHP memory limit if needed
3. **Timeout**: Run seeding during low-traffic hours
4. **Duplicate entries**: The seeder uses `updateOrCreate` to prevent duplicates

### Monitoring
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Monitor database
mysql -u [username] -p[password] -e "SELECT COUNT(*) FROM global_departments;"
mysql -u [username] -p[password] -e "SELECT COUNT(*) FROM global_locations;"
```

## ✅ Verification Checklist
- [ ] Database backup created
- [ ] Seeder tested in staging
- [ ] Production environment variables set
- [ ] Seeding command executed successfully
- [ ] Data verified in database
- [ ] Application functionality tested
- [ ] No errors in logs

## 📞 Support
If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify database connectivity
3. Ensure proper permissions
4. Contact development team if needed

---
**Note**: This seeder is designed to be safe for production use with `updateOrCreate` methods that prevent duplicate entries.
