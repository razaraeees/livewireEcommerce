<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersIndex extends Component
{
    use WithPagination;

    // Search and Filter Properties
    public $search = '';
    public $statusFilter = 'all';
    
    // Sidebar Filters
    public $orderId = '';
    public $customerName = '';
    public $minTotal = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'statusFilter',
            'orderId',
            'customerName',
            'minTotal',
            'dateFrom',
            'dateTo',
        ]);
        $this->resetPage();
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        
        if ($order) {
            $order->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Order deleted successfully!');

        } else {

            $this->dispatch('show-toast', type: 'error', message: 'Order not found!');


        }
    }

    public function getOrdersProperty()
    {
        $query = Order::query()->with('user');

        // Main Search (Order Number, Name, Email)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        // Status Filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Sidebar Filters
        if (!empty($this->orderId)) {
            $query->where('order_number', 'like', '%' . $this->orderId . '%');
        }

        if (!empty($this->customerName)) {
            $query->where('name', 'like', '%' . $this->customerName . '%');
        }

        if (!empty($this->minTotal)) {
            $query->where('grand_total', '>=', $this->minTotal);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->latest()->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.orders.orders-index', [
            'orders' => $this->orders,
        ]);
    }
}