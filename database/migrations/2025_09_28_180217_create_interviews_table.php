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
        Schema::create('interviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('application_id');
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes');
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->uuid('created_by');
            $table->timestamps();

            $table->index(['tenant_id', 'application_id']);
            $table->index(['tenant_id', 'scheduled_at']);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            // Note: users table uses auto-incrementing IDs, not UUIDs
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
