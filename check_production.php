#!/bin/bash

# Safe Production Check Script
# Run this FIRST to see what's missing without making changes

echo "🔍 Checking Production Database State..."
echo "======================================="

# Check migration status
echo "📋 Migration Status:"
php artisan migrate:status | grep -E "(Pending|Ran)"

# Check subscription plans
echo -e "\n📊 Subscription Plans:"
PLAN_COUNT=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::count();" 2>/dev/null)
echo "Found: $PLAN_COUNT plans"

if [ "$PLAN_COUNT" -eq 0 ]; then
    echo "❌ CRITICAL: No subscription plans found!"
    echo "   Organization creation will fail"
else
    echo "✅ Subscription plans exist"
fi

# Check free plan specifically
FREE_PLAN=$(php artisan tinker --execute="echo App\Models\SubscriptionPlan::where('slug', 'free')->exists() ? 'true' : 'false';" 2>/dev/null)
if [ "$FREE_PLAN" = "false" ]; then
    echo "❌ CRITICAL: Free plan missing!"
else
    echo "✅ Free plan exists"
fi

# Check custom role tables
echo -e "\n🗂️  Custom Role Tables:"
TABLES_EXIST=$(php artisan tinker --execute="echo DB::getSchemaBuilder()->hasTable('custom_tenant_roles') ? 'true' : 'false';" 2>/dev/null)
if [ "$TABLES_EXIST" = "false" ]; then
    echo "❌ custom_tenant_roles table missing"
else
    echo "✅ custom_tenant_roles table exists"
fi

TABLES_EXIST=$(php artisan tinker --execute="echo DB::getSchemaBuilder()->hasTable('custom_user_roles') ? 'true' : 'false';" 2>/dev/null)
if [ "$TABLES_EXIST" = "false" ]; then
    echo "❌ custom_user_roles table missing"
else
    echo "✅ custom_user_roles table exists"
fi

# Check activation_token column
echo -e "\n🔑 Activation Token Column:"
TOKEN_COLUMN=$(php artisan tinker --execute="echo Schema::hasColumn('users', 'activation_token') ? 'true' : 'false';" 2>/dev/null)
if [ "$TOKEN_COLUMN" = "false" ]; then
    echo "❌ activation_token column missing from users table"
else
    echo "✅ activation_token column exists"
fi

echo -e "\n📝 Summary:"
echo "If you see any ❌ above, those need to be fixed for organization creation to work."
echo "Run the safe-fix script only for the missing components."
