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
        Schema::table('candidates', function (Blueprint $table) {
            // Add onboarding-related fields
            $table->string('designation')->nullable()->after('source');
            $table->string('department')->nullable()->after('designation');
            $table->string('manager')->nullable()->after('department');
            $table->date('joining_date')->nullable()->after('manager');
            $table->integer('completed_steps')->default(0)->nullable()->after('joining_date');
            $table->integer('total_steps')->default(5)->nullable()->after('completed_steps');
            $table->string('status')->nullable()->after('total_steps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'designation',
                'department',
                'manager',
                'joining_date',
                'completed_steps',
                'total_steps',
                'status',
            ]);
        });
    }
};
