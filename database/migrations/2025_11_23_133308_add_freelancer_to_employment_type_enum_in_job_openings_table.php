<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter the enum to include 'freelancer'
        DB::statement("ALTER TABLE job_openings MODIFY COLUMN employment_type ENUM('full_time', 'part_time', 'contract', 'intern', 'freelancer') DEFAULT 'full_time'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE job_openings MODIFY COLUMN employment_type ENUM('full_time', 'part_time', 'contract', 'intern') DEFAULT 'full_time'");
    }
};
