<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCharges extends Model
{
    protected $fillable = [
        'fee',
        'type',
        'max_order_amount',
        'status',
    ];
}
