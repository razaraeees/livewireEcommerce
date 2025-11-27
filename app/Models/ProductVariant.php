<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'sale_price',
        'stock',
        'variant_slug',
        'combination_label',
        'weight',
        'length',
        'width',
        'height',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductVariantImages::class, 'product_variant_id');
    }

    public function variantValues()
    {
        return $this->hasMany(ProductVariantValue::class, 'product_variant_id');
    }



}
