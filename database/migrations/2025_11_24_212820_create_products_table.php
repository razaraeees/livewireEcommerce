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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();

            $table->string('product_name');
            $table->string('product_slug')->unique();  // auto indexed
            $table->string('product_code')->unique();  // auto indexed

            $table->string('product_color')->nullable();
            $table->decimal('product_price', 10, 2)->default(0);
            $table->decimal('product_discount', 10, 2)->default(0);

            $table->integer('product_weight')->nullable();
            $table->string('thumbnail_image')->nullable();

            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();

            $table->integer('stock')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->default('in_stock')->index();

            $table->boolean('is_featured')->default(false)->index();    
            $table->integer('order_by')->nullable();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();

            $table->tinyInteger('status')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
