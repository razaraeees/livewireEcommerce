<div class="dashboard-page-content">
    <div class="row mb-9 align-items-center justify-content-between">
        <div class="col-md-6 mb-8 mb-md-0">
            <h2 class="fs-4 mb-0">Annoucement List</h2>
            <p>View and manage all annoucements.</p>
        </div>
        <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
            <a href="{{ route('admin.annoucement.create') }}" class="btn btn-primary">
                Create new
            </a>
        </div>
    </div>
    <div class="card mb-4 rounded-4 p-7">
        <div class="card-header bg-transparent px-0 pt-0 pb-7">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-3 col-12 mr-auto mb-md-0 mb-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..."
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
                            <th>Title</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Start At</th>
                            <th>End At</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($annoucements as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($annoucements->currentPage() - 1) * $annoucements->perPage() }}
                                </td>
                                <td>
                                    <p class="fw-semibold text-body-emphasis mb-0">{{ $item->title ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="mt-3">{!! Str::limit($item->message, 50) !!}</p>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-info text-capitalize">{{ $item->type }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge rounded-lg rounded-pill alert py-3 px-4 mb-0 alert-{{ $item->is_active ? 'success' : 'danger' }} border-0 text-capitalize fs-12">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td>{{ $item->start_at ? $item->start_at->format('d.m.Y H:i') : 'N/A' }}</td>
                                <td>{{ $item->end_at ? $item->end_at->format('d.m.Y H:i') : 'N/A' }}</td>
                                <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        <a href="{{ route('admin.annoucement.edit', $item->id) }}"
                                            class="btn btn-primary py-4 px-5 btn-xs fs-13px me-4"><i
                                                class="far fa-pen me-2"></i> Edit</a>
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
                                            class="btn btn-outline-primary btn-hover-bg-danger btn-hover-border-danger btn-hover-text-light py-4 px-5 fs-13px btn-xs me-4">
                                            <i class="far fa-trash me-2"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No annoucements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($annoucements->hasPages())
        <nav aria-label="Page navigation example" class="mt-6 mb-4">
            <ul class="pagination justify-content-start">
                {{-- Previous Page Link --}}
                @if ($annoucements->onFirstPage())
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
                @foreach ($annoucements->links()->elements[0] as $page => $url)
                    @if ($page == $annoucements->currentPage())
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
                @if ($annoucements->hasMorePages())
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
