# Production Deployment Checklist for TalentLit

## 🚨 Critical Issues to Fix Before Production

### 1. Database Migrations
```bash
# Check migration status
php artisan migrate:status

# Run all pending migrations
php artisan migrate --force

# If migrations fail, run specific ones:
php artisan migrate --path=database/migrations/2025_10_05_124022_create_custom_roles_tables.php --force
php artisan migrate --path=database/migrations/2025_10_08_193707_add_activation_token_to_users_table.php --force
```

### 2. Subscription Plans
```bash
# Seed subscription plans (CRITICAL!)
php artisan db:seed --class=SubscriptionPlanSeeder --force

# Verify plans exist
php artisan tinker
# Then run: App\Models\SubscriptionPlan::count()
```

### 3. Custom Role Tables
```bash
# Verify tables exist
php artisan tinker
# Then run: DB::getSchemaBuilder()->hasTable('custom_tenant_roles')
# And: DB::getSchemaBuilder()->hasTable('custom_user_roles')
```

## 🔧 Production Deployment Commands

### Option 1: Automated Script
```bash
php deploy_production.php
```

### Option 2: Manual Steps
```bash
# 1. Backup database first!
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Run migrations
php artisan migrate --force

# 3. Seed subscription plans
php artisan db:seed --class=SubscriptionPlanSeeder --force

# 4. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## ✅ Verification Steps

### Test Organization Creation
1. Register a new user
2. Activate email
3. Try to create organization
4. Should redirect to dashboard successfully

### Check Database State
```sql
-- Verify subscription plans
SELECT COUNT(*) FROM subscription_plans;
SELECT * FROM subscription_plans WHERE slug = 'free';

-- Verify custom role tables exist
SHOW TABLES LIKE 'custom_%';

-- Check if activation_token column exists
DESCRIBE users;
```

## 🚨 Common Production Issues

### Issue 1: "Free subscription plan not found"
**Solution:** Run SubscriptionPlanSeeder
```bash
php artisan db:seed --class=SubscriptionPlanSeeder --force
```

### Issue 2: "Unknown column 'activation_token'"
**Solution:** Run activation token migration
```bash
php artisan migrate --path=database/migrations/2025_10_08_193707_add_activation_token_to_users_table.php --force
```

### Issue 3: "Table 'custom_tenant_roles' doesn't exist"
**Solution:** Run custom roles migration
```bash
php artisan migrate --path=database/migrations/2025_10_05_124022_create_custom_roles_tables.php --force
```

## 📋 Pre-Production Checklist

- [ ] Database backup created
- [ ] All migrations run successfully
- [ ] Subscription plans seeded
- [ ] Custom role tables exist
- [ ] Activation token column exists
- [ ] Organization creation tested
- [ ] Email activation tested
- [ ] Error logs monitored

## 🔍 Monitoring Commands

```bash
# Check application logs
tail -f storage/logs/laravel.log

# Monitor database queries
# (Enable query logging in config/database.php)

# Check migration status
php artisan migrate:status

# Verify seeded data
php artisan tinker
# App\Models\SubscriptionPlan::all()
```

## ⚠️ Important Notes

1. **Always backup before deployment**
2. **Test in staging environment first**
3. **Monitor logs after deployment**
4. **Have rollback plan ready**
5. **Subscription plans are CRITICAL - without them, organization creation fails**
6. **Custom role tables are REQUIRED - without them, user roles can't be assigned**
