<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user (Raza)
        $user = User::first();

        // Get all products
        $allProducts = Product::all();

        if ($allProducts->count() == 0) {
            $this->command->error('No products found! Please add products first.');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        for ($i = 0; $i < 6; $i++) {
            // Randomly select 1 to 5 products for this order
            $orderProducts = $allProducts->random(rand(1, min(5, $allProducts->count())));

            // Create test order
            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'session_id' => 'test-session-' . uniqid(),
                'name' => $user ? $user->name : 'Raza Ahmed',
                'email' => $user ? $user->email : 'raza@example.com',
                'mobile' => '+92 300 1234567',
                'address' => 'House # ' . rand(1, 999) . ', Street ' . rand(1, 99) . ', Block ' . chr(rand(65, 90)),
                'city' => 'Karachi',
                'state' => 'Sindh',
                'country' => 'Pakistan',
                'pincode' => '75500',
                'subtotal' => 0,
                'shipping_charges' => 150.00,
                'tax_amount' => 0,
                'coupon_amount' => 0,
                'grand_total' => 0,
                'payment_method' => 'Cash on Delivery',
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subDays(rand(0, 30)), // Random date in last 30 days
            ]);

            $subtotal = 0;

            // Add order items
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->product_discount ?? $product->product_price;
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_sku' => $product->product_code,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // Update order totals
            $grandTotal = $subtotal + $order->shipping_charges;
            $order->update([
                'subtotal' => $subtotal,
                'grand_total' => $grandTotal,
            ]);

            $this->command->info("✅ Order #{$order->order_number} created with status: {$order->status}");
        }

        $this->command->info('🎉 6 Test orders created successfully!');
    }
}
