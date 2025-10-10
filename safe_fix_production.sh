#!/bin/bash

# Safe Production Fix Script
# Only fixes what's actually missing - won't touch existing data

echo "🛠️  Safe Production Fix Script"
echo "=============================="
echo "This script will ONLY add missing components"
echo "It will NOT modify existing data"
echo ""

# Check what's missing first
echo "🔍 Checking what needs to be fixed..."

# Check subscription plans
PLAN_COUNT=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::count();" 2>/dev/null)
if [ "$PLAN_COUNT" -eq 0 ]; then
    echo "❌ Subscription plans missing - will add them"
    NEEDS_PLANS=true
else
    echo "✅ Subscription plans already exist"
    NEEDS_PLANS=false
fi

# Check custom role tables
TABLES_EXIST=$(php artisan tinker --execute="echo DB::getSchemaBuilder()->hasTable('custom_tenant_roles') ? 'true' : 'false';" 2>/dev/null)
if [ "$TABLES_EXIST" = "false" ]; then
    echo "❌ custom_tenant_roles table missing - will create it"
    NEEDS_ROLES=true
else
    echo "✅ custom_tenant_roles table exists"
    NEEDS_ROLES=false
fi

# Check activation_token column
TOKEN_COLUMN=$(php artisan tinker --execute="echo Schema::hasColumn('users', 'activation_token') ? 'true' : 'false';" 2>/dev/null)
if [ "$TOKEN_COLUMN" = "false" ]; then
    echo "❌ activation_token column missing - will add it"
    NEEDS_TOKEN=true
else
    echo "✅ activation_token column exists"
    NEEDS_TOKEN=false
fi

echo ""
echo "📋 What will be fixed:"
if [ "$NEEDS_PLANS" = "true" ]; then
    echo "  - Add subscription plans (Free, Pro, Enterprise)"
fi
if [ "$NEEDS_ROLES" = "true" ]; then
    echo "  - Create custom_tenant_roles table"
    echo "  - Create custom_user_roles table"
fi
if [ "$NEEDS_TOKEN" = "true" ]; then
    echo "  - Add activation_token column to users table"
fi

if [ "$NEEDS_PLANS" = "false" ] && [ "$NEEDS_ROLES" = "false" ] && [ "$NEEDS_TOKEN" = "false" ]; then
    echo "✅ Nothing needs to be fixed - all components exist!"
    exit 0
fi

echo ""
read -p "Continue with fixes? (y/N): " confirm
if [[ $confirm != [yY] ]]; then
    echo "❌ Fix cancelled"
    exit 1
fi

echo ""
echo "🔄 Applying fixes..."

# Fix 1: Add subscription plans if missing
if [ "$NEEDS_PLANS" = "true" ]; then
    echo "📊 Adding subscription plans..."
    php artisan db:seed --class=SubscriptionPlanSeeder --force
    echo "✅ Subscription plans added"
fi

# Fix 2: Add custom role tables if missing
if [ "$NEEDS_ROLES" = "true" ]; then
    echo "🗂️  Creating custom role tables..."
    php artisan migrate --path=database/migrations/2025_10_05_124022_create_custom_roles_tables.php --force
    echo "✅ Custom role tables created"
fi

# Fix 3: Add activation_token column if missing
if [ "$NEEDS_TOKEN" = "true" ]; then
    echo "🔑 Adding activation_token column..."
    php artisan migrate --path=database/migrations/2025_10_08_193707_add_activation_token_to_users_table.php --force
    echo "✅ activation_token column added"
fi

echo ""
echo "✅ All fixes applied successfully!"
echo "🎉 Organization creation should now work properly."

# Final verification
echo ""
echo "🔍 Final verification:"
PLAN_COUNT=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::count();" 2>/dev/null)
echo "📊 Subscription plans: $PLAN_COUNT"

FREE_PLAN=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::where('slug', 'free')->exists() ? 'true' : 'false';" 2>/dev/null)
if [ "$FREE_PLAN" = "true" ]; then
    echo "✅ Free plan exists"
else
    echo "❌ Free plan still missing"
fi
