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
        Schema::create('candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('primary_email');
            $table->string('primary_phone')->nullable();
            $table->string('source')->nullable();
            $table->longText('resume_raw_text')->nullable();
            $table->json('resume_json')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'primary_email']);
            $table->index(['tenant_id', 'source']);
            $table->unique(['tenant_id', 'primary_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
