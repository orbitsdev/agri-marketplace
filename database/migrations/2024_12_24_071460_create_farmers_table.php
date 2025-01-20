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
        
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('farm_name')->nullable();
            $table->text('location')->nullable();
            $table->string('farm_size')->nullable();
            $table->text('description')->nullable();
            $table->string('contact')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Blocked'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
