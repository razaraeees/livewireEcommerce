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
        Schema::create('product_variant_values', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('variant_value_id');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('product_variant_id')
                ->references('id')->on('product_variants')
                ->onDelete('cascade');

            $table->foreign('variant_id')
                ->references('id')->on('variants')
                ->onDelete('cascade');

            $table->foreign('variant_value_id')
                ->references('id')->on('variant_values')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_values');
    }
};
