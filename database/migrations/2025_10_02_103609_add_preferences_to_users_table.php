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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_weekly_summaries')->default(true);
            $table->boolean('notify_new_applications')->default(true);
            $table->boolean('send_marketing_emails')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_weekly_summaries', 'notify_new_applications', 'send_marketing_emails']);
        });
    }
};
