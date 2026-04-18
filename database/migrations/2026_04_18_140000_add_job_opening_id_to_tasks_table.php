<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('job_opening_id')->nullable()->after('requisition_id');
            $table->index(['job_opening_id', 'status']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('job_opening_id')
                ->references('id')
                ->on('job_openings')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['job_opening_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('job_opening_id');
        });
    }
};
