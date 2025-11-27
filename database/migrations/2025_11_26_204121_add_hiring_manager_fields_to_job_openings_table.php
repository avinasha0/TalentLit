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
        Schema::table('job_openings', function (Blueprint $table) {
            $table->string('hiring_manager_primary_name')->nullable()->after('description');
            $table->string('hiring_manager_primary_email')->nullable()->after('hiring_manager_primary_name');
            $table->string('hiring_manager_primary_phone')->nullable()->after('hiring_manager_primary_email');
            $table->string('hiring_manager_secondary_name')->nullable()->after('hiring_manager_primary_phone');
            $table->string('hiring_manager_secondary_email')->nullable()->after('hiring_manager_secondary_name');
            $table->string('hiring_manager_secondary_phone')->nullable()->after('hiring_manager_secondary_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropColumn([
                'hiring_manager_primary_name',
                'hiring_manager_primary_email',
                'hiring_manager_primary_phone',
                'hiring_manager_secondary_name',
                'hiring_manager_secondary_email',
                'hiring_manager_secondary_phone',
            ]);
        });
    }
};
