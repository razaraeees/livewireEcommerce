<div>
    <div class="dashboard-page-content">
        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-md-6 mb-8 mb-md-0">
                <h2 class="fs-4 mb-0">SEO Pages</h2>
                <p>Manage page meta tags and SEO settings.</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
                <a href="{{ route('admin.seo.create') }}" class="btn btn-primary">
                    Create New Page
                </a>
            </div>
        </div>

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-3 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search by page name or URL..." class="form-control bg-input border-0">
                    </div>
                </div>
            </div>

            <div class="card-body px-0 pt-7 pb-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Page</th>
                                <th>Page URL</th>
                                <th>Meta Title</th>
                                <th>Meta Description</th>
                                {{-- <th>Meta Keywords</th> --}}
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($pages->currentPage() - 1) * $pages->perPage() }}</td>

                                    <td>{{ $item->page_name }}</td>
                                    <td>{{ Str::limit($item->page_url, 40)  }}</td>
                                    <td>{{ Str::limit($item->meta_title, 40) }}</td>
                                    <td>{{ Str::limit($item->meta_description, 60) }}</td>
                                    {{-- <td>{{ Str::limit($item->meta_keywords, 50) }}</td> --}}

                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>

                                    <td class="text-center">
                                        <div class="d-flex flex-nowrap justify-content-center">
                                            <a href="{{ route('admin.seo.edit', $item->id) }}"
                                                class="btn btn-primary py-4 px-5 btn-xs fs-13px me-4">
                                                <i class="far fa-pen me-2"></i> 
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
                                                class="btn btn-outline-primary btn-hover-bg-danger btn-hover-border-danger btn-hover-text-light py-4 px-5 fs-13px btn-xs me-4">
                                                <i class="far fa-trash me-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No SEO pages found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($pages->hasPages())
            <nav aria-label="Page navigation example" class="mt-6 mb-4">
                <ul class="pagination justify-content-start">
                    @if ($pages->onFirstPage())
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

                    @foreach ($pages->links()->elements[0] as $page => $url)
                        @if ($page == $pages->currentPage())
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

                    @if ($pages->hasMorePages())
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
