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
        Schema::table('newsletter_subscriptions', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('email');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
            $table->string('ip_address')->nullable()->after('verified_at');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['is_verified', 'verified_at', 'ip_address', 'user_agent']);
        });
    }
};