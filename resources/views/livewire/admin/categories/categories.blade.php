<div>
    <div class="dashboard-page-content">
        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-md-6 mb-8 mb-md-0">
                <h2 class="fs-4 mb-0">Category List</h2>
                <p>Manage all your product categories</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="far fa-plus me-2"></i> Create new
                </a>
            </div>
        </div>

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-3 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..."
                            class="form-control bg-input border-0">
                    </div>
                    <div class="col-md-3 col-12">
                        <select wire:model.live="filterStatus" class="form-control bg-input border-0">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 pt-7 pb-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Category</th>
                                <th>Parent</th>
                                <th>Level</th>
                                <th>URL</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Banner</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center flex-nowrap">
                                            @if ($item->category_image)
                                                <a href="#" title="{{ $item->category_name }}">
                                                    <img src="{{ asset('storage/' . $item->category_image) }}"
                                                        data-src="{{ asset('storage/' . $item->category_image) }}"
                                                        alt="{{ $item->category_name }}" class="lazy-image rounded"
                                                        width="60" height="60" style="object-fit: cover;">
                                                </a>
                                            @endif
                                            <div class="ms-3">
                                                <p class="fw-semibold text-body-emphasis mb-0">
                                                    {{ $item->category_name }}</p>
                                                @if ($item->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($item->description, 40) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->parent)
                                            <span
                                                class="badge bg-light text-dark">{{ $item->parent->category_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">Level {{ $item->level }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->url }}</small>
                                    </td>
                                    <td>
                                        @if ($item->category_discount)
                                            <span class="badge bg-success">{{ $item->category_discount }}%</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-lg rounded-pill alert py-3 px-4 mb-0 alert-{{ $item->status ? 'success' : 'danger' }} border-0 text-capitalize fs-12">
                                            {{ $item->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->category_banner)
                                            <span
                                                class="badge bg-{{ $item->banner_status ? 'success' : 'secondary' }}">
                                                <i class="far fa-{{ $item->banner_status ? 'check' : 'times' }}"></i>
                                                {{ $item->banner_status ? 'Visible' : 'Hidden' }}
                                            </span>
                                        @else
                                            <span class="text-muted">No Banner</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-nowrap justify-content-center">
                                            <a href="{{ route('admin.categories.edit', $item->url) }}"
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
                                    <td colspan="10" class="text-center">No Categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($categories->hasPages())
            <nav aria-label="Page navigation example" class="mt-6 mb-4">
                <ul class="pagination justify-content-start">
                    {{-- Previous Page Link --}}
                    @if ($categories->onFirstPage())
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

                    {{-- Pagination Elements --}}
                    @foreach ($categories->links()->elements[0] as $page => $url)
                        @if ($page == $categories->currentPage())
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
                    @if ($categories->hasMorePages())
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
