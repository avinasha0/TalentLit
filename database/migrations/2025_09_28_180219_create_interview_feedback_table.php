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
        Schema::create('interview_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('interview_id');
            $table->uuid('user_id');
            $table->tinyInteger('rating')->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'interview_id']);
            $table->foreign('interview_id')->references('id')->on('interviews')->onDelete('cascade');
            // Note: users table uses auto-incrementing IDs, not UUIDs
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_feedback');
    }
};
