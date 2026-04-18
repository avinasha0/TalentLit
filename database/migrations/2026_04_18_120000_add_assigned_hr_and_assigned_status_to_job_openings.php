<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_openings', function (Blueprint $table) {
            $table->foreignId('assigned_hr_user_id')
                ->nullable()
                ->after('tenant_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE job_openings MODIFY COLUMN status ENUM('draft', 'assigned', 'published', 'closed') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("UPDATE job_openings SET status = 'draft' WHERE status = 'assigned'");
            DB::statement("ALTER TABLE job_openings MODIFY COLUMN status ENUM('draft', 'published', 'closed') NOT NULL DEFAULT 'draft'");
        }

        Schema::table('job_openings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_hr_user_id');
        });
    }
};
