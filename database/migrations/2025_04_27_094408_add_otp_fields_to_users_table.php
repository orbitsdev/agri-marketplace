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
            $table->string('otp_code')->nullable()->after('remember_token');
            $table->unsignedTinyInteger('otp_attempts')->default(3)->after('otp_code');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_attempts', 'otp_expires_at']);
        });
    }
};
