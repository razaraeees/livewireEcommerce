<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

        protected $fillable = [
            'category_id',
            'brand_id',
            'product_name',
            'product_slug',
            'product_code',
            'product_price',
            'product_discount',
            'product_weight',
            'thumbnail_image',
            'short_description',
            'long_description',
            'stock',
            'stock_status',
            'is_featured',
            'order_by',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'status',
        ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Product images
    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }

    // Product.php
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }


}
