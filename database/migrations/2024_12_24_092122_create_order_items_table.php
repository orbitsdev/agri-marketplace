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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->double('price_per_unit')->nullable();
            $table->double('total_price')->nullable();
            $table->double('subtotal', 10, 2)->nullable(); // Total price for this item (quantity * price_per_unit)

            $table->string('product_name')->nullable();
$table->text('product_description')->nullable();
$table->string('short_description')->nullable();
$table->double('product_price')->nullable();
$table->integer('product_quantity')->nullable(); // Snapshot of the product's stock at the time of order


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
