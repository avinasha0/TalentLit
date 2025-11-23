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
        // Change status from enum to string for flexibility
        \DB::statement("ALTER TABLE applications MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum (keeping original values)
        \DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('active', 'withdrawn', 'hired', 'rejected') NOT NULL DEFAULT 'active'");
    }
};
