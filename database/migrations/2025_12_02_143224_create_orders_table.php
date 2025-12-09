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
            
            // Session and User tracking
            $table->string('session_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            
            // Order identification
            $table->string('order_number')->unique();
            
            // Customer Information
            $table->string('name');
            $table->string('email')->index();
            $table->string('mobile', 20);
            
            // Shipping Address
            $table->text('address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);
            $table->string('pincode', 20);
            
            // Pricing
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_charges', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('coupon_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            
            // Coupon
            $table->string('coupon_code')->nullable()->index();
            
            // Payment Information
            $table->string('payment_method')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->enum('payment_status', [
                'unpaid',
                'paid',
                'failed'
            ])->default('unpaid')->index();
            $table->string('transaction_id')->nullable()->index();
            
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Additional tracking
            $table->boolean('is_pushed')->default(0);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
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
