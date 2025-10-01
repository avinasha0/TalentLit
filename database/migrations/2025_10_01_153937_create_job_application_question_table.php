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
        Schema::create('job_application_question', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('job_id');
            $table->uuid('question_id');
            $table->integer('sort_order');
            $table->boolean('required_override')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('job_openings')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('application_questions')->onDelete('cascade');
            $table->unique(['tenant_id', 'job_id', 'question_id']);
            $table->index(['tenant_id', 'job_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application_question');
    }
};