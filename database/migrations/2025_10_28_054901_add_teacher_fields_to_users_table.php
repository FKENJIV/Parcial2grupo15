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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('password');
            $table->string('code')->nullable()->after('role');
            $table->string('type')->nullable()->after('code');
            $table->string('phone')->nullable()->after('type');
            $table->string('status')->default('active')->after('phone');
            $table->text('specialties')->nullable()->after('status');
            $table->string('api_token', 80)->unique()->nullable()->after('specialties');
            $table->timestamp('api_token_expires_at')->nullable()->after('api_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'code',
                'type',
                'phone',
                'status',
                'specialties',
                'api_token',
                'api_token_expires_at',
            ]);
        });
    }
};
