<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImages extends Model
{
    protected $fillable = [
        'product_variant_id',
        'alt_text',
        'image',
        'sort_order',
    ];
}
