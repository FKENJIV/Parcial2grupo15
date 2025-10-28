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
            // 'role' was added in an earlier migration; only add teacher-specific fields here
            $table->string('code')->nullable()->after('password');
            $table->string('type')->nullable()->after('code');
            $table->string('phone')->nullable()->after('type');
            $table->string('status')->default('active')->after('phone');
            $table->text('specialties')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'type',
                'phone',
                'status',
                'specialties',
            ]);
        });
    }
};
