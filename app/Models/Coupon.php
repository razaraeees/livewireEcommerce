<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code_type',
        'coupon_code',
        'coupon_type',
        'discount_type',
        'discount_value',
        'minimum_purchase_amount',
        'maximum_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_limit_per_user',
        'status',
        'description',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'coupon_brands');
    }

}
