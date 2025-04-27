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
        Schema::create('farmer_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->onDelete('cascade');
            $table->foreignId('requirement_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['Pending', 'Submitted', 'Approved', 'Rejected'])->default('Pending');
            $table->boolean('is_checked')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Ensure a farmer can't have duplicate requirements
            $table->unique(['farmer_id', 'requirement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_requirements');
    }
};
