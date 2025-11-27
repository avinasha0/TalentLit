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
        Schema::table('requisitions', function (Blueprint $table) {
            $table->integer('duration')->nullable()->after('contract_type')->comment('Duration in months for intern/contract types');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium')->after('headcount');
            $table->string('location', 200)->nullable()->after('priority')->comment('City or Remote');
            $table->text('additional_notes')->nullable()->after('justification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn(['duration', 'priority', 'location', 'additional_notes']);
        });
    }
};
