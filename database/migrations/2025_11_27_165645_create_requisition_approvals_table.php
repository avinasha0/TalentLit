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
        Schema::create('requisition_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedBigInteger('approver_id');
            $table->enum('action', ['Pending', 'Approved', 'Rejected', 'RequestedChanges', 'Delegated'])->default('Pending');
            $table->text('comments')->nullable();
            $table->integer('approval_level');
            $table->unsignedBigInteger('delegate_to')->nullable();
            $table->timestamps();

            $table->index('requisition_id');
            $table->index('approver_id');
            $table->index(['requisition_id', 'approval_level']);
            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('delegate_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_approvals');
    }
};
