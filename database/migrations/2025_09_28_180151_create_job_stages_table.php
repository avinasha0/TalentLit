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
        Schema::create('job_stages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('job_opening_id');
            $table->string('name');
            $table->integer('sort_order');
            $table->boolean('is_terminal')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'job_opening_id', 'sort_order']);
            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_stages');
    }
};
