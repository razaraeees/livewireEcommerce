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
        Schema::create('order_transsections', function (Blueprint $table) {

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('transaction_id')->nullable(); 
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('PKR');

            $table->string('payment_method'); 
            $table->string('payment_status')->default('pending'); 

            $table->json('transaction_response')->nullable(); 
            $table->timestamp('paid_at')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transsections');
    }
};
