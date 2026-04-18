<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add Moved To Finance to requisitions.status (MySQL ENUM).
     * SQLite stores enums as unconstrained strings; no change required there.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE requisitions MODIFY COLUMN status ENUM('Draft', 'Pending', 'Moved To Finance', 'Approved', 'Rejected') NOT NULL DEFAULT 'Draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::table('requisitions')->where('status', 'Moved To Finance')->update(['status' => 'Pending']);

        DB::statement("ALTER TABLE requisitions MODIFY COLUMN status ENUM('Draft', 'Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Draft'");
    }
};
