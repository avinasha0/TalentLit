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
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index(); // assignee
            $table->string('task_type', 100);
            $table->string('title', 255);
            $table->unsignedBigInteger('requisition_id')->nullable()->index();
            $table->enum('status', ['Pending', 'InProgress', 'Completed', 'Cancelled'])->default('Pending');
            $table->dateTime('due_at')->nullable();
            $table->string('link', 512)->nullable(); // deep link to UI
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['requisition_id', 'status']);
            $table->index('due_at');
            
            // Foreign keys (optional - commented out as per user requirements)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
