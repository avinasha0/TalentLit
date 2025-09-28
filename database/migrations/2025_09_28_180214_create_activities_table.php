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
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('application_id')->nullable();
            $table->uuid('candidate_id')->nullable();
            $table->enum('type', ['task', 'call', 'email', 'event']);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('title');
            $table->text('body')->nullable();
            $table->uuid('user_id');
            $table->timestamps();

            $table->index(['tenant_id', 'application_id']);
            $table->index(['tenant_id', 'candidate_id']);
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'due_at']);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            // Note: users table uses auto-incrementing IDs, not UUIDs
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
