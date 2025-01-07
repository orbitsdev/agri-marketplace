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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')
            ->nullable() // Make the column nullable
            ->constrained('farmers') // Foreign key constraint
            ->onDelete('set null'); // Set to null if the farmer is deleted
            $table->double('price')->nullable();
            $table->enum('status', ['Pending', 'Confirm', 'Cancelled','Completed'])->default('Pending');
            $table->double('total', 10, 2)->nullable(); // Total price for the order

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
