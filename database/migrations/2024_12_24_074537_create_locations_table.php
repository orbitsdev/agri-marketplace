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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('region')->nullable();
            $table->string('region_code')->nullable(); // Add region code
            $table->string('province')->nullable();
            $table->string('province_code')->nullable(); // Add province code
            $table->string('city_municipality')->nullable();
            $table->string('city_code')->nullable(); // Add city/municipality code
            $table->string('barangay')->nullable();
            $table->string('barangay_code')->nullable(); // Add barangay code
            $table->string('phone')->nullable();

            $table->string('street')->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
