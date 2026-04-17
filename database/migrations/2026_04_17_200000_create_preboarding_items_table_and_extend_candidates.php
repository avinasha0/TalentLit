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
            $table->string('buddy_name')->nullable()->after('manager');
            $table->string('buddy_email')->nullable()->after('buddy_name');
            $table->string('hr_contact_name')->nullable()->after('buddy_email');
            $table->string('hr_contact_email')->nullable()->after('hr_contact_name');
            $table->string('welcome_kit_status')->default('pending')->after('hr_contact_email');
            $table->timestamp('preboarding_started_at')->nullable()->after('welcome_kit_status');
        });

        Schema::create('preboarding_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('candidate_id');
            $table->uuid('application_id')->nullable();
            $table->string('track'); // document | it | benefits | checklist
            $table->string('item_key');
            $table->string('title');
            $table->string('status')->default('pending'); // pending | in_progress | completed | waived
            $table->boolean('requires_esign')->default(false);
            $table->string('esign_status')->nullable(); // pending | sent | signed
            $table->string('assigned_to_name')->nullable();
            $table->string('assigned_to_email')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'candidate_id']);
            $table->index(['tenant_id', 'track', 'status']);
            $table->unique(['candidate_id', 'item_key']);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preboarding_items');

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'buddy_name',
                'buddy_email',
                'hr_contact_name',
                'hr_contact_email',
                'welcome_kit_status',
                'preboarding_started_at',
            ]);
        });
    }
};

