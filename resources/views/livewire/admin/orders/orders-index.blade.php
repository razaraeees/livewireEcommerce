<div>
    <div class="dashboard-page-content">

        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-sm-6 mb-8 mb-sm-0">
                <h2 class="fs-4 mb-0">Order List</h2>
                <p class="mb-0">Manage all your orders</p>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="card mb-4 rounded-4 p-7">
            <div class="card-body">
                <h5 class="mb-6 fs-6">Filter Orders</h5>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="mb-2 fs-13px">Order ID</label>
                        <input type="text" wire:model.defer="orderId" placeholder="Order ID" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-2 fs-13px">Customer</label>
                        <input type="text" wire:model.defer="customerName" placeholder="Customer name"
                            class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-2 fs-13px">Status</label>
                        <select wire:model.defer="statusFilter" class="form-select">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-2 fs-13px">Min Total</label>
                        <input type="number" wire:model.defer="minTotal" placeholder="Min amount" class="form-control"
                            step="0.01">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-2 fs-13px">Date From</label>
                        <input type="date" wire:model.defer="dateFrom" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="mb-2 fs-13px">Date To</label>
                        <input type="date" wire:model.defer="dateTo" class="form-control">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-5">
                    <button type="button" wire:click="applyFilters" class="btn btn-sm  btn-primary">
                        <i class="far fa-filter me-2"></i> Apply Filters
                    </button>
                    <button type="button" wire:click="clearFilters" class="btn btn-sm btn-outline-secondary">
                        <i class="far fa-times me-2"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 rounded-4 p-7">
                    <div class="card-header bg-transparent px-0 pt-0 pb-7">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-md-4 col-12 mr-auto mb-md-0 mb-6">
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    placeholder="Search by Order ID, Name, Email..."
                                    class="form-control bg-input border-0">
                            </div>
                            <div class="col-md-8">
                                <div class="row justify-content-end flex-nowrap d-flex">
                                    <div class="col-lg-3 col-md-6 col-6">
                                        <select wire:model.live="statusFilter" class="form-select">
                                            <option value="all">All Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-lg-3 col-md-6 col-6">
                                        <select wire:model.live="perPage" class="form-select">
                                            <option value="20">Show 20</option>
                                            <option value="30">Show 30</option>
                                            <option value="40">Show 40</option>
                                            <option value="50">Show 50</option>
                                            <option value="100">Show 100</option>
                                        </select>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0 pt-7 pb-0">
                        {{-- Success/Error Messages --}}
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Loading Indicator --}}
                        <div wire:loading class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive" wire:loading.remove>
                            <table class="table table-hover align-middle table-nowrap mb-0 table-borderless">
                                <thead class="table-light">
                                    <tr>
                                        <th class="align-middle" scope="col">#ID</th>
                                        <th class="align-middle" scope="col">Customer Name</th>
                                        <th class="align-middle" scope="col">Total</th>
                                        <th class="align-middle" scope="col">Status</th>
                                        <th class="align-middle" scope="col">Date</th>
                                        <th class="align-middle text-center" scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>
                                                <a href="#">
                                                    #{{ $order->order_number }}
                                                </a>
                                            </td>

                                            <td class="text-body-emphasis">
                                                {{ $order->name }}
                                                <br>
                                                <small class="text-muted">{{ $order->email }}</small>
                                            </td>

                                            <td>
                                                <strong>${{ number_format($order->grand_total, 2) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $order->payment_method ?? 'N/A' }}</small>
                                            </td>

                                            <td>
                                                @php
                                                    $statusClass = match ($order->status) {
                                                        'pending' => 'alert-warning',
                                                        'processing' => 'alert-info',
                                                        'completed' => 'alert-success',
                                                        'cancelled' => 'alert-danger',
                                                        default => 'alert-secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge rounded-lg rounded-pill alert py-3 px-4 mb-0 {{ $statusClass }} border-0 text-capitalize fs-12">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $order->created_at->format('d.m.Y') }}
                                                <br>
                                                <small
                                                    class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex flex-nowrap justify-content-center">
                                                    <a href="{{ route('admin.orders.detail', $order->id) }}"
                                                        class="btn btn-primary py-4 fs-13px btn-xs me-4">
                                                        Detail
                                                    </a>
                                                    <button x-data
                                                        @click="
                                                        $event.preventDefault();
                                                        Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: 'You won\'t be able to revert this!',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#d33',
                                                            cancelButtonColor: '#3085d6',
                                                            confirmButtonText: 'Yes, delete it!',
                                                            cancelButtonText: 'Cancel'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                $wire.deleteOrder({{ $order->id }})
                                                            }
                                                        })
                                                    "
                                                        class="btn btn-outline-primary btn-hover-bg-danger btn-hover-border-danger btn-hover-text-light py-4 px-5 fs-13px btn-xs me-4">
                                                        <i class="far fa-trash me-2"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="far fa-inbox fa-3x mb-3 d-block"></i>
                                                    <p class="mb-0">No orders found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-6 mb-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
