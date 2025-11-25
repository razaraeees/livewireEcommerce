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
        Schema::table('product_variant_images', function (Blueprint $table) {

            // Yahan sahi column check karo:
            if (!Schema::hasColumn('product_variant_images', 'product_variant_id')) {
                $table->unsignedBigInteger('product_variant_id')->after('id');
            }

            // Ab foreign key add karo:
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variant_images', function (Blueprint $table) {
            //
        });
    }
};
