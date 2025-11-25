<div>
    <div class="dashboard-page-content">
        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-md-6 mb-8 mb-md-0">
                <h2 class="fs-4 mb-0">Product List</h2>
                <p>Manage all your products</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                    <i class="far fa-plus me-2"></i> Create new
                </a>
            </div>
        </div>

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-3 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                            class="form-control bg-input border-0">
                    </div>
                    <div class="col-md-2 col-12 mb-md-0 mb-6">
                        <select wire:model.live="filterStatus" class="form-control bg-input border-0">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-12 mb-md-0 mb-6">
                        <select wire:model.live="filterCategory" class="form-control bg-input border-0">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-12 mb-md-0 mb-6">
                        <select wire:model.live="filterBrand" class="form-control bg-input border-0">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-12">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="far fa-redo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 pt-7 pb-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Variants</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center flex-nowrap">
                                            @if($item->thumbnail_image)
                                                <a href="#" title="{{ $item->product_name }}">
                                                    <img src="{{ asset('storage/' . $item->thumbnail_image) }}"
                                                        data-src="{{ asset('storage/' . $item->thumbnail_image) }}"
                                                        alt="{{ $item->product_name }}" class="lazy-image rounded"
                                                        width="60" height="60" style="object-fit: cover;">
                                                </a>
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                    style="width: 60px; height: 60px;">
                                                    <i class="far fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="ms-3">
                                                <p class="fw-semibold text-body-emphasis mb-0">{{ $item->product_name }}</p>
                                                @if($item->product_code)
                                                    <small class="text-muted">Code: {{ $item->product_code }}</small>
                                                @endif
                                                @if($item->theme)
                                                    <br><span class="badge bg-info mt-1" style="font-size: 10px;">{{ ucfirst($item->theme) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($item->category)
                                            <span class="badge bg-light text-dark">{{ $item->category->category_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($item->brand)
                                            <span class="badge bg-light text-dark">{{ $item->brand->brand_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="fw-bold">Rs {{ number_format($item->product_price) }}</span>
                                        @if($item->product_discount)
                                            <br><span class="badge bg-success">-{{ $item->product_discount }}%</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill py-2 px-3 
                                            alert-{{ $item->stock > 0 ? 'success' : 'danger' }} border-0 fs-12">
                                            {{ $item->stock ?? 0 }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill py-2 px-3 alert-primary border-0 fs-12">
                                            {{ $item->variants->count() }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge rounded-lg rounded-pill alert py-3 px-4 mb-0 alert-{{ $item->status ? 'success' : 'danger' }} border-0 text-capitalize fs-12">
                                            {{ $item->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($item->is_featured)
                                            <span class="badge bg-warning">
                                                <i class="far fa-star"></i> Featured
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>

                                    <td class="text-center">
                                        <div class="d-flex flex-nowrap justify-content-center">
                                            <a href="{{ route('admin.product.edit', $item->product_slug) }}"
                                                class="btn btn-primary py-4 px-5 btn-xs fs-13px me-2" title="Edit">
                                                <i class="far fa-pen"></i>
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
                                                        $wire.delete({{ $item->id }})
                                                    }
                                                })
                                            "
                                                class="btn btn-outline-primary btn-hover-bg-danger btn-hover-border-danger btn-hover-text-light py-4 px-5 fs-13px btn-xs"
                                                title="Delete">
                                                <i class="far fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No Products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($products->hasPages())
            <nav aria-label="Page navigation example" class="mt-6 mb-4">
                <ul class="pagination justify-content-start">
                    @if ($products->onFirstPage())
                        <li class="page-item disabled mx-3">
                            <span class="page-link"><i class="far fa-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item mx-3">
                            <button type="button" class="page-link" wire:click="previousPage"
                                wire:loading.attr="disabled">
                                <i class="far fa-chevron-left"></i>
                            </button>
                        </li>
                    @endif

                    @foreach ($products->links()->elements[0] as $page => $url)
                        @if ($page == $products->currentPage())
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

                    @if ($products->hasMorePages())
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
</div>