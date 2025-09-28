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
        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('attachable_type');
            $table->uuid('attachable_id');
            $table->string('disk');
            $table->string('path');
            $table->string('filename');
            $table->string('mime');
            $table->bigInteger('size');
            $table->timestamp('created_at');

            $table->index(['tenant_id', 'attachable_type', 'attachable_id']);
            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
