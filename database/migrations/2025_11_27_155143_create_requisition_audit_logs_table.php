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
        Schema::create('requisition_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // created, updated, status_changed, deleted, etc.
            $table->string('field_name')->nullable(); // Field that was changed
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('changes')->nullable(); // JSON of all changes
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['requisition_id', 'created_at']);
            $table->index('user_id');
            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_audit_logs');
    }
};
