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
        // Create custom_tenant_roles table
        if (!Schema::hasTable('custom_tenant_roles')) {
            Schema::create('custom_tenant_roles', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id', 36);
                $table->string('name');
                $table->json('permissions');
                $table->timestamps();
                
                $table->unique(['tenant_id', 'name'], 'unique_tenant_role');
                $table->index('tenant_id');
            });
        }

        // Create custom_user_roles table
        if (!Schema::hasTable('custom_user_roles')) {
            Schema::create('custom_user_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('tenant_id', 36);
                $table->string('role_name');
                $table->timestamps();
                
                $table->unique(['user_id', 'tenant_id', 'role_name'], 'unique_user_tenant_role');
                $table->index('user_id');
                $table->index('tenant_id');
            });
        }

        // Create email_verification_otps table if it doesn't exist
        if (!Schema::hasTable('email_verification_otps')) {
            Schema::create('email_verification_otps', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->string('otp', 6);
                $table->timestamp('expires_at');
                $table->boolean('is_used')->default(false);
                $table->timestamp('used_at')->nullable();
                $table->timestamps();
                
                $table->index('email');
                $table->index('expires_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_user_roles');
        Schema::dropIfExists('custom_tenant_roles');
        Schema::dropIfExists('email_verification_otps');
    }
};