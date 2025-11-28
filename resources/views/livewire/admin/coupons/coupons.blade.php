<div class="dashboard-page-content">
    <div class="row mb-9 align-items-center justify-content-between">
        <div class="col-md-6 mb-8 mb-md-0">
            <h2 class="fs-4 mb-0">Coupon List</h2>
            <p>Lorem ipsum dolor sit amet.</p>
        </div>
        <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
            <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">
                Create new
            </a>
        </div>
    </div>
    <div class="card mb-4 rounded-4 p-7">
        <div class="card-header bg-transparent px-0 pt-0 pb-7">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-3 col-12 mr-auto mb-md-0 mb-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search coupon..."
                        class="form-control bg-input border-0">
                </div>
            </div>
        </div>

        <div class="card-body px-0 pt-7 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Coupon Code</th>
                            <th>Discount</th>
                            <th>Coupon Type</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Expired Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($couponDate as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($couponDate->currentPage() - 1) * $couponDate->perPage() }}
                                </td>

                                <td>{{ $item->coupon_code }}</td>

                                <td>
                                    @if ($item->discount_type == 'percentage')
                                        {{ $item->discount_value }}%
                                    @else
                                        PKR {{ number_format($item->discount_value) }}
                                    @endif
                                </td>

                                <td>{{ ucfirst(str_replace('_', ' ', $item->coupon_type)) }}</td>

                                <td>
                                    <span
                                        class="badge rounded-pill alert py-3 px-4 mb-0 alert-{{ $item->status ? 'success' : 'danger' }} border-0">
                                        {{ $item->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>{{ $item->start_date ? date('d.m.Y', strtotime($item->start_date)) : '-' }}</td>
                                <td>{{ $item->end_date ? date('d.m.Y', strtotime($item->end_date)) : '-' }}</td>

                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">

                                        <a href="{{ route('admin.coupon.edit', $item->id) }}"
                                            class="btn btn-primary py-4 px-5 btn-xs fs-13px me-4">
                                            <i class="far fa-pen me-2"></i> Edit
                                        </a>

                                        <button x-data
                                            @click="
                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'This coupon will be deleted!',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'Yes, delete it!',
                                                    cancelButtonText: 'Cancel'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.delete({{ $item->id }})
                                                    }
                                                })
                                            "
                                            class="btn btn-outline-danger py-4 px-5 fs-13px btn-xs me-4">
                                            <i class="far fa-trash me-2"></i> Delete
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No coupons found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    @if ($couponDate->hasPages())
        <nav aria-label="Page navigation example" class="mt-6 mb-4">
            <ul class="pagination justify-content-start">
                {{-- Previous Page Link --}}
                @if ($couponDate->onFirstPage())
                    <li class="page-item disabled mx-3">
                        <span class="page-link"><i class="far fa-chevron-left"></i></span>
                    </li>
                @else
                    <li class="page-item mx-3">
                        <button type="button" class="page-link" wire:click="previousPage" wire:loading.attr="disabled">
                            <i class="far fa-chevron-left"></i>
                        </button>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($couponDate->links()->elements[0] as $page => $url)
                    @if ($page == $couponDate->currentPage())
                        <li class="page-item active mx-3" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item mx-3">
                            <button type="button" class="page-link"
                                wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($couponDate->hasMorePages())
                    <li class="page-item mx-3">
                        <button type="button" class="page-link" wire:click="nextPage" wire:loading.attr="disabled">
                            <i class="far fa-chevron-right"></i>
                        </button>
                    </li>
                @else
                    <li class="page-item disabled mx-3">
                        <span class="page-link"><i class="far fa-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
