<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('email');
            $table->string('name');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['tenant_id', 'email']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->uuid('candidate_account_id')->nullable()->after('tenant_id');
            $table->foreign('candidate_account_id')->references('id')->on('candidate_accounts')->nullOnDelete();
        });

        if (Schema::hasColumn('candidates', 'user_id')) {
            $rows = DB::table('candidates')->whereNotNull('user_id')->get(['id', 'tenant_id', 'user_id']);
            foreach ($rows as $row) {
                $user = DB::table('users')->where('id', $row->user_id)->first();
                if (!$user) {
                    continue;
                }

                $email = strtolower(trim($user->email));
                $existing = DB::table('candidate_accounts')
                    ->where('tenant_id', $row->tenant_id)
                    ->where('email', $email)
                    ->value('id');

                if ($existing) {
                    $accountId = $existing;
                } else {
                    $accountId = (string) Str::uuid();
                    DB::table('candidate_accounts')->insert([
                        'id' => $accountId,
                        'tenant_id' => $row->tenant_id,
                        'email' => $email,
                        'name' => $user->name,
                        'password' => $user->password,
                        'email_verified_at' => $user->email_verified_at,
                        'remember_token' => $user->remember_token,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('candidates')->where('id', $row->id)->update(['candidate_account_id' => $accountId]);
            }

            Schema::table('candidates', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('candidates', 'candidate_account_id')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->dropForeign(['candidate_account_id']);
                $table->dropColumn('candidate_account_id');
            });
        }

        Schema::dropIfExists('candidate_accounts');

        if (! Schema::hasColumn('candidates', 'user_id')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }
};
