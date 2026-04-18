<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('offer_response', 32)->nullable()->after('expected_ctc');
            $table->timestamp('offer_responded_at')->nullable()->after('offer_response');
            $table->text('offer_discussion_message')->nullable()->after('offer_responded_at');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['offer_response', 'offer_responded_at', 'offer_discussion_message']);
        });
    }
};
