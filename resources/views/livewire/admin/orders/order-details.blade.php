<div>
    <div class="dashboard-page-content">

        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-sm-6 mb-8 mb-sm-0">
                <h2 class="fs-4 mb-0">Order Details</h2>
                <p class="mb-0">Order #{{ $order->order_number }}</p>
            </div>
        </div>

        <div class="card rounded-4">
            <header class="card-header bg-transparent p-7">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-lg-0 mb-6">
                        <span class="d-inline-block">
                            <i class="far fa-calendar me-3"></i>{{ $order->created_at->format('D, M d, Y, h:i A') }}
                        </span>
                        <br>
                        <small class="text-muted">Order ID: {{ $order->order_number }}</small>
                    </div>
                    <div class="col-md-6 ml-auto d-flex justify-content-md-end flex-wrap">
                        <div class="mw-210 me-5 my-3">
                            <select class="form-select" wire:model="selectedStatus">
                                <option value="">Change status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <button wire:click="updateStatus" class="btn btn-primary my-3">Save</button>
                        <a class="btn btn-dark print ms-5 my-3" href="#" onclick="window.print()">
                            <i class="far fa-print"></i>
                        </a>
                    </div>
                </div>
            </header>

            <div class="card-body p-7">
                <div class="row mb-8 mt-4 order-info-wrap">
                    {{-- Customer Info --}}
                    <div class="col-md-4 mb-md-0 mb-7">
                        <div class="d-flex flex-nowrap">
                            <div class="icon-wrap">
                                <span class="rounded-circle px-6 py-5 bg-green-light me-6 text-green d-inline-block">
                                    <i class="fas fa-user px-1"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <h6 class="mb-4">Customer</h6>
                                <p class="mb-4">
                                    {{ $order->name }}<br>
                                    {{ $order->email }}<br>
                                    {{ $order->mobile }}
                                </p>
                                @if ($order->user_id)
                                    <a href="#" class="btn-link-custom">View profile</a>
                                @else
                                    <span class="text-muted">Guest User</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Order Info --}}
                    <div class="col-md-4 mb-md-0 mb-7">
                        <div class="d-flex flex-nowrap">
                            <div class="icon-wrap">
                                <span class="rounded-circle p-5 bg-green-light me-6 text-green d-inline-block">
                                    <i class="fas fa-truck px-2"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <h6 class="mb-4">Order Info</h6>
                                <p class="mb-4">
                                    Shipping: {{ $order->courier_name ?? 'Not assigned' }}<br>
                                    Pay method: {{ $order->payment_method ?? 'N/A' }}<br>
                                    Status: <span class="text-capitalize fw-bold">{{ $order->status }}</span>
                                </p>
                                @if ($order->tracking_number)
                                    <p class="mb-0"><strong>Tracking:</strong> {{ $order->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Delivery Address --}}
                    <div class="col-md-4">
                        <div class="d-flex flex-nowrap">
                            <div class="icon-wrap">
                                <span class="rounded-circle p-5 bg-green-light me-6 text-green d-inline-block">
                                    <i class="fas fa-map-marker-alt px-2"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <h6 class="mb-4">Deliver to</h6>
                                <p class="mb-4">
                                    {{ $order->address }}<br>
                                    {{ $order->city }}, {{ $order->state }}<br>
                                    {{ $order->country }} - {{ $order->pincode }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7">
                        {{-- Order Items Table --}}
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center flex-nowrap">
                                                    @if ($item->product && $item->product->thumbnail_image)
                                                        <img src="{{ asset('storage/' . $item->product->thumbnail_image) }}"
                                                            alt="{{ $item->product_name }}" width="60"
                                                            height="70" class="me-3">
                                                    @endif
                                                    <div>
                                                        <p class="fw-semibold text-body-emphasis mb-0">
                                                            {{ $item->product_name }}
                                                        </p>
                                                        @if ($item->variant_name)
                                                            <small class="text-muted">{{ $item->variant_name }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No items found</td>
                                        </tr>
                                    @endforelse

                                    {{-- Order Summary --}}
                                    <tr>
                                        <td colspan="4">
                                            <div class="d-flex flex-column align-items-end justify-content-end">
                                                <div class="mw-40 w-40">
                                                    <div class="d-flex w-100">
                                                        <span class="d-inline-block w-50">Subtotal:</span>
                                                        <span class="d-inline-block w-50 text-end fw-normal">
                                                            ${{ number_format($order->subtotal, 2) }}
                                                        </span>
                                                    </div>
                                                    @if ($order->tax_amount > 0)
                                                        <div class="d-flex w-100">
                                                            <span class="d-inline-block w-50">Tax:</span>
                                                            <span class="d-inline-block w-50 text-end fw-normal">
                                                                ${{ number_format($order->tax_amount, 2) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex w-100">
                                                        <span class="d-inline-block w-50">Shipping:</span>
                                                        <span class="d-inline-block w-50 text-end fw-normal">
                                                            ${{ number_format($order->shipping_charges, 2) }}
                                                        </span>
                                                    </div>
                                                    @if ($order->coupon_amount > 0)
                                                        <div class="d-flex w-100">
                                                            <span class="d-inline-block w-50">Discount
                                                                ({{ $order->coupon_code }}):</span>
                                                            <span
                                                                class="d-inline-block w-50 text-end fw-normal text-success">
                                                                -${{ number_format($order->coupon_amount, 2) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex w-100 mb-5">
                                                        <span class="d-inline-block w-50">Grand total:</span>
                                                        <span class="d-inline-block w-50 text-end fs-5 fw-semibold">
                                                            ${{ number_format($order->grand_total, 2) }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex w-100">
                                                        <span class="d-inline-block w-50 text-muted">Status:</span>
                                                        <span class="d-inline-block w-50 text-end fs-20 fw-semibold">
                                                            @php
                                                                $statusClass = match ($order->status) {
                                                                    'pending' => 'alert-warning text-warning',
                                                                    'processing' => 'alert-info text-info',
                                                                    'completed' => 'alert-success text-success',
                                                                    'cancelled' => 'alert-danger text-danger',
                                                                    default => 'alert-secondary text-secondary',
                                                                };
                                                            @endphp
                                                            <span
                                                                class="badge rounded-pill alert {{ $statusClass }} fs-12px px-4 py-3 text-capitalize">
                                                                {{ $order->status }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4 offset-lg-1">
                        {{-- Payment Info --}}
                        <div class="box shadow-sm bg-body-tertiary p-6 mb-4">
                            <h6 class="mb-6">Payment Info</h6>
                            <div>
                                <p class="mb-2">
                                    <strong>Method:</strong> {{ $order->payment_method ?? 'N/A' }}
                                </p>
                                @if ($order->payment_gateway)
                                    <p class="mb-2">
                                        <strong>Gateway:</strong> {{ $order->payment_gateway }}
                                    </p>
                                @endif
                                @if ($order->transaction_id)
                                    <p class="mb-2">
                                        <strong>Transaction ID:</strong> {{ $order->transaction_id }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Status History --}}
                        @if ($statusHistories->count() > 0)
                            <div class="box shadow-sm bg-body-tertiary p-6 mb-4">
                                <h6 class="mb-6">Status History</h6>
                                <div class="timeline">
                                    @foreach ($statusHistories as $history)
                                        <div class="mb-3">
                                            <small
                                                class="text-muted">{{ $history->created_at->format('M d, Y h:i A') }}</small>
                                            <p class="mb-0 text-capitalize">
                                                <strong>{{ $history->order_status }}</strong>
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Notes Section --}}
                        <div class="h-25 pt-4">
                            <div class="mb-6">
                                <label class="mb-5 fs-13px ls-1 fw-semibold text-uppercase">Notes</label>
                                <textarea class="form-control" wire:model="notes" rows="4" placeholder="Type some note"></textarea>
                            </div>
                            <button wire:click="saveNotes" class="btn btn-primary">Save note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
