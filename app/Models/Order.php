<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'session_id',
        'user_id',
        'order_number',
        'name',
        'email',
        'mobile',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'subtotal',
        'shipping_charges',
        'tax_amount',
        'coupon_amount',
        'grand_total',
        'coupon_code',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'courier_name',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'is_pushed',
        'notes',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_charges' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'coupon_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'is_pushed' => 'boolean',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    // Helper Methods
    public function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = $order->generateOrderNumber();
            }
        });
    }
}
