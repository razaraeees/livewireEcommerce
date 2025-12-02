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
            
            // Order reference
            $table->unsignedBigInteger('order_id')->index();
            
            // Product reference
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('product_variant_id')->nullable()->index();
            
            // Product details (snapshot at time of order)
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('variant_name')->nullable();
            $table->text('variant_attributes')->nullable(); // JSON for color, size, etc
            
            // Pricing
            $table->decimal('price', 10, 2); // Unit price
            $table->integer('quantity')->default(1);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2); // (price * quantity) - discount + tax
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
                  
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('restrict');
                  
            $table->foreign('product_variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('set null');
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
