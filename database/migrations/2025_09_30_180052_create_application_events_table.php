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
        Schema::create('application_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('application_id');
            $table->uuid('job_id');
            $table->uuid('from_stage_id')->nullable();
            $table->uuid('to_stage_id')->nullable();
            $table->uuid('user_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'application_id']);
            $table->index(['tenant_id', 'job_id']);
            $table->index(['tenant_id', 'user_id']);
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('job_openings')->onDelete('cascade');
            $table->foreign('from_stage_id')->references('id')->on('job_stages')->onDelete('set null');
            $table->foreign('to_stage_id')->references('id')->on('job_stages')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_events');
    }
};
