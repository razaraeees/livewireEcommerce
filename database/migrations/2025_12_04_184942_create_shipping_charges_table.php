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
        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            $table->decimal('fee', 10, 2)->default(0); // Shipping fee
            $table->enum('type', ['flat', 'percentage'])->default('flat'); // Fee type
            $table->decimal('max_order_amount', 10, 2)->nullable(); // Max cart total for fee to apply
            $table->boolean('status')->default(1); // Active / Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_charges');
    }
};
