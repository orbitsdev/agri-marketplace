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
            $table->id(); //
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Product reference
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Buyer reference
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade'); // Farmer reference
            $table->text('content'); // Comment content
            $table->timestamps(); // Created at & Updated at timestamps

            // Add indexes for efficient lookups
            $table->index(['product_id', 'buyer_id', 'farmer_id']);
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
