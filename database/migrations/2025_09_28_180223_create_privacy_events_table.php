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
        Schema::create('privacy_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('candidate_id')->nullable();
            $table->enum('type', ['erase', 'anonymize', 'export']);
            $table->enum('status', ['pending', 'done', 'failed'])->default('pending');
            $table->json('payload')->nullable();
            $table->timestamp('created_at');

            $table->index(['tenant_id', 'candidate_id']);
            $table->index(['tenant_id', 'status']);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_events');
    }
};
