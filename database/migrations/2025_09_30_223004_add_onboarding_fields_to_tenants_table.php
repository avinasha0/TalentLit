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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('website')->nullable()->after('slug');
            $table->string('location')->nullable()->after('website');
            $table->string('company_size')->nullable()->after('location');
            $table->string('logo')->nullable()->after('company_size');
            $table->string('primary_color', 7)->nullable()->after('logo');
            $table->boolean('careers_enabled')->default(true)->after('primary_color');
            $table->text('careers_intro')->nullable()->after('careers_enabled');
            $table->text('consent_text')->nullable()->after('careers_intro');
            $table->string('timezone')->default('Asia/Kolkata')->after('consent_text');
            $table->string('locale', 10)->default('en')->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'location', 
                'company_size',
                'logo',
                'primary_color',
                'careers_enabled',
                'careers_intro',
                'consent_text',
                'timezone',
                'locale'
            ]);
        });
    }
};