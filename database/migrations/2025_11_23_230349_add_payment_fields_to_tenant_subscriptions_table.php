<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            // Add payment-related columns if they don't exist
            if (!Schema::hasColumn('tenant_subscriptions', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('tenant_subscriptions', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_id');
            }
            if (!Schema::hasColumn('tenant_subscriptions', 'currency')) {
                $table->string('currency', 3)->nullable()->after('amount_paid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_subscriptions', function (Blueprint $table) {
            // Remove payment-related columns if they exist
            if (Schema::hasColumn('tenant_subscriptions', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('tenant_subscriptions', 'amount_paid')) {
                $table->dropColumn('amount_paid');
            }
            if (Schema::hasColumn('tenant_subscriptions', 'payment_id')) {
                $table->dropColumn('payment_id');
            }
        });
    }
};
