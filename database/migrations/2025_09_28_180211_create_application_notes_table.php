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
        Schema::create('application_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('application_id');
            $table->uuid('user_id');
            $table->text('body');
            $table->timestamp('created_at');

            $table->index(['tenant_id', 'application_id']);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            // Note: users table uses auto-incrementing IDs, not UUIDs
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_notes');
    }
};
