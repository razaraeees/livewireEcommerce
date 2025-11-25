 <div>
     <div class="dashboard-page-content">
         <div class="row mb-9 align-items-center justify-content-between">
             <div class="col-md-6 mb-8 mb-md-0">
                 <h2 class="fs-4 mb-0">Banner List</h2>
                 <p>Lorem ipsum dolor sit amet.</p>
             </div>
             <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
                 <a href="{{ route('admin.banner.create') }}" class="btn btn-primary">
                     Create new
                 </a>
             </div>
         </div>
         <div class="card mb-4 rounded-4 p-7">
             <div class="card-header bg-transparent px-0 pt-0 pb-7">
                 <div class="row align-items-center justify-content-between">
                     <div class="col-md-4 col-12 mr-auto mb-md-0 mb-6">
                         <select wire:model.live="type" class="form-select">
                             <option value="">All Banners</option>
                             <option value="Main Hero Banner">Main Hero Banner</option>
                             <option value="Middle Banner">Middle Banner</option>
                             <option value="Annoucement Banner">Annoucement Banner</option>
                             <option value="Offer Banner">Offer Banner</option>
                         </select>
                     </div>
                     <div class="col-md-4 col-12">
                         <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title..."
                             class="form-control bg-input border-0">
                     </div>
                 </div>
             </div>
             <div class="card-body px-0 pt-7 pb-0">
                 <div class="table-responsive">
                     <table class="table table-hover align-middle table-nowrap mb-0">
                         <thead>
                             <tr>
                                 {{-- <th></th> --}}
                                 <th>Banner</th>
                                 <th>Type</th>
                                 <th>Status</th>
                                 <th>Created At</th>
                                 <th class="text-center" >Actions</th>
                             </tr>
                         </thead>
                         <tbody>
                             @forelse ($banners as $banner)
                                 <tr>

                                     <td>
                                         <div class="d-flex align-items-center flex-nowrap">
                                             <a href="#" title="{{ $banner->title }}">
                                                 <img src="{{ asset('storage/' . $banner->image) }}"
                                                     data-src="{{ asset('storage/' . $banner->image) }}"
                                                     alt="{{ $banner->title }}" class="lazy-image" height="70" width="100"
                                                     height="80">
                                             </a>
                                             <a href="#" title="{{ $banner->title }}" class="ms-6">
                                                 <p class="fw-semibold text-body-emphasis mb-0">{{ $banner->title }}</p>
                                             </a>
                                         </div>
                                     </td>
                                     <td>
                                         <span class="badge bg-primary">{{ $banner->type }}</span>
                                     </td>
                                     <td>
                                         <span
                                             class="badge rounded-lg rounded-pill alert py-3 px-4 mb-0 alert-{{ $banner->status ? 'success' : 'danger' }} border-0 text-capitalize fs-12">{{ $banner->status ? 'Active' : 'Inactive' }}</span>
                                     </td>
                                     <td>{{ $banner->created_at->format('d.m.Y') }}</td>
                                     <td class="text-center">
                                         <div class="d-flex flex-nowrap justify-content-center">
                                             <a href="{{ route('admin.banner.edit', $banner->id) }}"
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
                                                            $wire.delete({{ $banner->id }})
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
                                     <td colspan="6" class="text-center">No banners found.</td>
                                 </tr>
                             @endforelse
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
         @if ($banners->hasPages())
             <nav aria-label="Page navigation example" class="mt-6 mb-4">
                 <ul class="pagination justify-content-start">
                     {{-- Previous Page Link --}}
                     @if ($banners->onFirstPage())
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
                     @foreach ($banners->links()->elements[0] as $page => $url)
                         @if ($page == $banners->currentPage())
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
                     @if ($banners->hasMorePages())
                         <li class="page-item mx-3">
                             <button type="button" class="page-link" wire:click="nextPage"
                                 wire:loading.attr="disabled">
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
