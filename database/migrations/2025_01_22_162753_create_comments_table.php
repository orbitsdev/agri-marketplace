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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Product reference
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Buyer reference
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade'); // Farmer reference
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Allow replies
            $table->text('content'); // Message content
            $table->enum('creator', [
                'Buyer',
                'Farmer',
                'Admin',

            ])->default('Buyer');
            $table->boolean('is_read')->default(false); // Message content
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['product_id', 'buyer_id', 'farmer_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
