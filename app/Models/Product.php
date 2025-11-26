<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    



}
