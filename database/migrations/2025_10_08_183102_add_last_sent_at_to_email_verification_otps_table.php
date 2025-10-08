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
        Schema::table('email_verification_otps', function (Blueprint $table) {
            $table->timestamp('last_sent_at')->nullable()->after('used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_verification_otps', function (Blueprint $table) {
            $table->dropColumn('last_sent_at');
        });
    }
};
