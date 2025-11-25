<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    protected $fillable = [
        'product_variant_id',
        'variant_id',
        'variant_value_id',
    ];
}
