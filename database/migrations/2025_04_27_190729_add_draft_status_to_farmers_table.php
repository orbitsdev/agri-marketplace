<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum to include 'Draft'
        DB::statement("ALTER TABLE farmers MODIFY COLUMN status ENUM('Pending', 'Approved', 'Rejected', 'Blocked', 'Draft') NOT NULL DEFAULT 'Draft'");
        
        // Update any existing records with NULL status to 'Draft'
        DB::table('farmers')->whereNull('status')->update(['status' => 'Draft']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the default back to 'Pending'
        DB::statement("ALTER TABLE farmers MODIFY COLUMN status ENUM('Pending', 'Approved', 'Rejected', 'Blocked', 'Draft') NOT NULL DEFAULT 'Pending'");
    }
};
