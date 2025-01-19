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
        Schema::create('order_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Link to orders
            $table->string('current_location')->nullable(); // Current location of the order
            $table->string('destination')->nullable(); // Destination of the order
            $table->string('status')->nullable()->default('In Transit'); // Status of the movement
 
            $table->text('remarks')->nullable(); // Additional comments or notes
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // User who updated this
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_movements');
    }
};
