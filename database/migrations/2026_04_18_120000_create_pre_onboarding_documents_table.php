<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_onboarding_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('application_id');
            $table->uuid('candidate_id');
            $table->string('document_key', 64);
            $table->string('title');
            $table->boolean('is_required')->default(true);
            $table->string('status', 24)->default('pending');
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->uuid('uploaded_by_candidate_account_id')->nullable();
            $table->unsignedBigInteger('uploaded_by_user_id')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by_user_id')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'document_key']);
            $table->foreign('tenant_id', 'po_docs_tenant_fk')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('application_id', 'po_docs_application_fk')->references('id')->on('applications')->cascadeOnDelete();
            $table->foreign('candidate_id', 'po_docs_candidate_fk')->references('id')->on('candidates')->cascadeOnDelete();
            $table->foreign('uploaded_by_candidate_account_id', 'po_docs_upl_cand_acct_fk')->references('id')->on('candidate_accounts')->nullOnDelete();
            $table->foreign('uploaded_by_user_id', 'po_docs_upl_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->foreign('verified_by_user_id', 'po_docs_ver_user_fk')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_onboarding_documents');
    }
};
