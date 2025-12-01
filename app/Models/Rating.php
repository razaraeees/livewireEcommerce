<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable= [
        'user_id',
        'product_id',
        'review',
        'rating',
        'status'
    ];

public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}

public function product()
{
    return $this->belongsTo(\App\Models\Product::class, 'product_id');
}

}
