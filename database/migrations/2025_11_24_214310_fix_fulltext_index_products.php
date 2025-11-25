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
        Schema::table('products', function (Blueprint $table) {

            // Add correct short name fulltext index
            $table->fullText(
                ['product_name', 'short_description', 'long_description'],
                'prod_ft_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('products', function (Blueprint $table) {
        //         $table->dropFullText('prod_ft_index');
        //     });  
    }
};
