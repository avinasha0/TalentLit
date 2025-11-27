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
        Schema::table('requisitions', function (Blueprint $table) {
            $table->enum('approval_status', ['Draft', 'Pending', 'ChangesRequested', 'Approved', 'Rejected'])
                ->default('Draft')
                ->after('status');
            $table->integer('approval_level')->default(0)->after('approval_status');
            $table->unsignedBigInteger('current_approver_id')->nullable()->after('approval_level');
            $table->timestamp('approved_at')->nullable()->after('current_approver_id');
            $table->json('approval_workflow')->nullable()->after('approved_at');
            
            $table->index('current_approver_id');
            $table->index(['approval_status', 'current_approver_id']);
            $table->foreign('current_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropForeign(['current_approver_id']);
            $table->dropIndex(['approval_status', 'current_approver_id']);
            $table->dropIndex(['current_approver_id']);
            $table->dropColumn([
                'approval_status',
                'approval_level',
                'current_approver_id',
                'approved_at',
                'approval_workflow'
            ]);
        });
    }
};
