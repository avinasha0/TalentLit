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
        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('candidate_id');
            $table->string('disk');
            $table->string('path');
            $table->string('filename');
            $table->string('mime');
            $table->bigInteger('size');
            $table->timestamp('parsed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'candidate_id']);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
