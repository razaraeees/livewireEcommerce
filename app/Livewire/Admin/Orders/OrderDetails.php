<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Livewire\Component;

class OrderDetails extends Component
{
    public $orderId;
    public $order;
    public $orderItems;
    public $statusHistories;
    public $selectedStatus;
    public $paymentStatus;
    public $notes;

    public function mount($id)
    {
        $this->orderId = $id;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = Order::with(['user', 'items.product', 'items.productVariant'])
            ->findOrFail($this->orderId);

        $this->orderItems = $this->order->items;
        $this->selectedStatus = $this->order->status;
        $this->paymentStatus  = $this->order->payment_status;
        $this->notes = $this->order->notes;

        // Load status histories
        $this->statusHistories = OrderStatusHistory::where('order_id', $this->orderId)
            ->latest()
            ->get();
    }

    public function updatePaymentStatus()
    {
        $this->validate([
            'paymentStatus' => 'required|in:paid,unpaid',
        ]);

        $this->order->update([
            'payment_status' => $this->paymentStatus,
        ]);

        $this->dispatch('show-toast', type: 'success', message: 'Payment status updated successfully!');
    }

    public function updateStatus()
    {
        $this->validate([
            'selectedStatus' => 'required|in:pending,processing,completed,cancelled,delivered,refund',
        ]);

        // Update order status
        $this->order->update([
            'status' => $this->selectedStatus,
        ]);

        // Create status history
        OrderStatusHistory::create([
            'order_id' => $this->orderId,
            'order_status' => $this->selectedStatus,
        ]);

        // Update shipped_at or delivered_at timestamps
        if ($this->selectedStatus === 'completed' && !$this->order->delivered_at) {
            $this->order->update(['delivered_at' => now()]);
        }

        $this->loadOrder();
        $this->dispatch('show-toast', type: 'success', message: 'Order status updated successfully!');
    }

    public function saveNotes()
    {
        $this->order->update([
            'notes' => $this->notes,
        ]);

        $this->dispatch('show-toast', type: 'success', message: 'Notes saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.orders.order-details');
    }
}
