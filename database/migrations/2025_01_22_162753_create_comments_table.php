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
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('cascade'); // Buyer reference
            $table->foreignId('farmer_id')->nullable()->constrained('farmers')->onDelete('cascade'); // Farmer reference
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Allows replies
            $table->text('content'); // Comment content
            $table->timestamps();

            $table->index(['product_id']);
            $table->index(['buyer_id']);
            $table->index(['farmer_id']);
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
