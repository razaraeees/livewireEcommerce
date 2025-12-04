<?php

namespace App\Livewire\Admin\Shipping;

use App\Models\ShippingCharges;
use Livewire\Component;

class ShippingIndex extends Component
{

    public $fee;
    public $type;
    public $max_order_amount;
    public $status;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $settings = ShippingCharges::first();

        if ($settings) {
            $this->fee = $settings->fee;
            $this->type = $settings->type;
            $this->max_order_amount = $settings->max_order_amount;
            $this->status = $settings->status;
        }
    }

    public function store()
    {
        $validated = $this->validate([
            'fee' => 'required|numeric|min:0',
            'type' => 'required|in:flat,percentage',
            'max_order_amount' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        $settings = ShippingCharges::first();

        if ($settings) {
            $settings->update($validated);
            $this->dispatch('show-toast', type: 'success', message: 'Shipping Settings Updated Successfully!');
        } else {
            ShippingCharges::create($validated);
            $this->dispatch('show-toast', type: 'success', message: 'Shipping Settings Created Successfully!');
        }
    }

    public function render()
    {
        return view('livewire.admin.shipping.shipping-index');
    }
}