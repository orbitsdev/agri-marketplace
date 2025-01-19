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
            $table->string('order_number')->unique()->nullable();
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->nullable()->constrained('farmers')->onDelete('set null');
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('city_municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_reference')->nullable();
            $table->enum('status', [
                'Pending',
                'Processing',
                'Confirmed',
                'Shipped',
                'Out for Delivery',
                'Completed',
                'Cancelled',
                'Returned'
            ])->default('Processing');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamp('shipped_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->double('total', 10, 2)->nullable();

            $table->boolean('is_received')->default(false);
            $table->text('remarks')->nullable();
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
