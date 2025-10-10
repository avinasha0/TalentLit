#!/bin/bash

# Production Deployment Script for TalentLit
# Run this after syncing your repo to production

echo "🚀 Deploying TalentLit to Production..."

# 1. Run all pending migrations
echo "📋 Running migrations..."
php artisan migrate --force

# 2. Seed subscription plans (CRITICAL!)
echo "📊 Seeding subscription plans..."
php artisan db:seed --class=SubscriptionPlanSeeder --force

# 3. Clear and cache for production
echo "⚡ Optimizing for production..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Verify critical components
echo "✅ Verifying deployment..."

# Check if subscription plans exist
PLAN_COUNT=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::count();")
echo "📊 Subscription plans: $PLAN_COUNT"

if [ "$PLAN_COUNT" -eq 0 ]; then
    echo "❌ CRITICAL: No subscription plans found!"
    echo "Organization creation will fail without subscription plans."
    exit 1
fi

# Check if free plan exists
FREE_PLAN=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::where('slug', 'free')->exists() ? 'true' : 'false';")
if [ "$FREE_PLAN" = "false" ]; then
    echo "❌ CRITICAL: Free subscription plan missing!"
    exit 1
fi

echo "✅ Deployment completed successfully!"
echo "🎉 Organization creation should now work properly."
