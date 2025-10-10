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
        Schema::table('tenant_branding', function (Blueprint $table) {
            $table->text('company_description')->nullable()->after('intro_subtitle');
            $table->text('benefits_text')->nullable()->after('company_description');
            $table->string('contact_email')->nullable()->after('benefits_text');
            $table->string('contact_phone', 50)->nullable()->after('contact_email');
            $table->string('linkedin_url')->nullable()->after('contact_phone');
            $table->string('twitter_url')->nullable()->after('linkedin_url');
            $table->string('facebook_url')->nullable()->after('twitter_url');
            $table->boolean('show_benefits')->default(true)->after('facebook_url');
            $table->boolean('show_company_info')->default(true)->after('show_benefits');
            $table->boolean('show_social_links')->default(true)->after('show_company_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_branding', function (Blueprint $table) {
            $table->dropColumn([
                'company_description',
                'benefits_text',
                'contact_email',
                'contact_phone',
                'linkedin_url',
                'twitter_url',
                'facebook_url',
                'show_benefits',
                'show_company_info',
                'show_social_links',
            ]);
        });
    }
};
