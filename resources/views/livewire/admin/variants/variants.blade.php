<div>
    <div class="dashboard-page-content">
        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-md-6 mb-8 mb-md-0">
                <h2 class="fs-4 mb-0">variants List</h2>
                <p>Manage all your product variants</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
                <a href="{{ route('admin.variant.create') }}" class="btn btn-primary">
                    <i class="far fa-plus me-2"></i> Create new
                </a>
            </div>
        </div>

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

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-3 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search variants..."
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
                                <th>Variants Name</th>
                                <th>Values</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($variants as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($variants->currentPage() - 1) * $variants->perPage() }}
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $item->name }}</span>
                                    </td>
                                    <td>
                                        @if($item->variantValues && $item->variantValues->count() > 0)
                                            @foreach($item->variantValues as $val)
                                                @php
                                                    $values = is_string($val->value)
                                                        ? explode(',', $val->value)
                                                        : [$val->value];
                                                @endphp

                                                @foreach($values as $value)
                                                    <span class="badge bg-primary text-white me-1 mb-1">{{ trim($value) }}</span>
                                                @endforeach
                                            @endforeach
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
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-nowrap justify-content-center">
                                            <a href="{{ route('admin.variant.edit', $item->slug) }}"
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
                                    <td colspan="6" class="text-center">No Variants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($variants->hasPages())
            <nav aria-label="Page navigation example" class="mt-6 mb-4">
                <ul class="pagination justify-content-start">
                    @if ($variants->onFirstPage())
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

                    @foreach ($variants->links()->elements[0] as $page => $url)
                        @if ($page == $variants->currentPage())
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

                    @if ($variants->hasMorePages())
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