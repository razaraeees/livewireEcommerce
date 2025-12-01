<div class="dashboard-page-content">
    <div class="row mb-9 align-items-center justify-content-between">
        <div class="col-md-6 mb-8 mb-md-0">
            <h2 class="fs-4 mb-0">Reviews List</h2>
            <p class="text-muted mb-0">Manage customer reviews and ratings</p>
        </div>
        <div class="col-md-6 d-flex flex-wrap justify-content-md-end">
            <button class="btn btn-primary">
                <i class="fas fa-download me-2"></i>Export Reviews
            </button>
        </div>
    </div>

    <div class="card mb-4 rounded-4 p-7">
        <div class="card-header bg-transparent px-0 pt-0 pb-7">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-4 col-12 mr-auto mb-md-0 mb-6">
                    <input type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Search reviews..." 
                            class="form-control bg-input border-0">
                </div>
                <div class="col-md-8">
                    <div class="row justify-content-end flex-nowrap d-flex">
                        <div class="col-lg-3 col-md-6 col-6">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="20">Show 20</option>
                                <option value="30">Show 30</option>
                                <option value="40">Show 40</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-6">
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="all">All Status</option>
                                <option value="approved">Approved</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body px-0 pt-7 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap mb-0 table-borderless">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle">ID</th>
                            <th class="align-middle">User</th>
                            <th class="align-middle">Product</th>
                            <th class="align-middle">Review</th>
                            <th class="align-middle">Rating</th>
                            <th class="align-middle">Created At</th>
                            <th class="align-middle text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr wire:key="review-{{ $review->id }}">
                                {{-- ID --}}
                                <td>
                                    <span class="text-muted fw-semibold">#{{ $review->id }}</span>
                                </td>

                                {{-- User --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $review->user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=4e7661&color=fff' }}" 
                                                alt="{{ $review->user->name }}" 
                                                width="40" 
                                                height="40"
                                                class="rounded-circle object-fit-cover me-3">
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $review->user->name }}</p>
                                            <small class="text-muted">{{ $review->user->email }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Product - Only Name --}}
                                <td>
                                    <p class="fw-semibold mb-0">{{ $review->product->product_name }}</p>
                                </td>

                                {{-- Review Text --}}
                                <td>
                                    <p class="mb-0 text-muted" style="max-width: 250px;">
                                        {{ Str::limit($review->review, 60) }}
                                    </p>
                                </td>

                                {{-- Rating Stars --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="fw-bold">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                
                                {{-- Created At --}}
                                <td>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $review->created_at->format('d M, Y') }}</span>
                                        <small class="text-muted">{{ $review->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        <button 
                                            wire:click="viewDetail({{ $review->id }})"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reviewDetailModal"
                                            class="btn btn-outline-primary btn-hover-text-light py-4 px-5 fs-13px btn-xs me-3">
                                            <i class="fas fa-eye me-1"></i>View
                                        </button>
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
                                                        $wire.delete({{ $review->id }})
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
                                <td colspan="7" class="text-center py-5">
                                    <i class="far fa-comments fs-1 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">No reviews found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($reviews->hasPages())
    <nav aria-label="Page navigation example" class="mt-6 mb-4">
        <ul class="pagination justify-content-start">
            @if($reviews->onFirstPage())
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

            @foreach($reviews->links()->elements[0] as $page => $url)
                @if($page == $reviews->currentPage())
                    <li class="page-item active mx-3" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item mx-3">
                        <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                    </li>
                @endif
            @endforeach

            @if($reviews->hasMorePages())
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

    @if($selectedReview)
        <div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-labelledby="reviewDetailModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    <div class="card mb-0 rounded-4">
                        <div class="card-header p-15" style="background-color: #B88539; height: 120px;"></div>
                        
                        <div class="card-body p-7">
                            <div class="row">
                                <div class="col-xl col-lg flex-grow-0 mb-xl-0 mb-7" style="flex-basis: 230px">
                                    <div class="img-thumbnail shadow w-100 bg-body position-relative text-center mt-n20 ">
                                        <img class="img-fluid rounded-3" 
                                                src="{{ $selectedReview->user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedReview->user->name) . '&background=4e7661&color=fff&size=180&font-size=0.35' }}" 
                                                alt="{{ $selectedReview->user->name }}" 
                                                width="200" 
                                                height="200"
                                                style="object-fit: cover;">
                                    </div>
                                </div>
                                
                                <div class="col-xl col-lg">
                                    <h3 class="fs-4 mb-0">{{ $selectedReview->user->name }}</h3>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-envelope me-2"></i>{{ $selectedReview->user->email }}
                                    </p>
                                    {{-- RATING and STATUS in MODAL --}}
                                    <div class="d-flex align-items-center mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $selectedReview->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <span class="fw-bold ms-2">{{ $selectedReview->rating }}/5</span>
                                    </div>
                                    <span class="badge rounded-pill {{ $selectedReview->status ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} px-3 py-1">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                        {{ $selectedReview->status ? 'Approved' : 'Pending' }}
                                    </span>
                                </div>
                                
                                <div class="col-xl-4 text-xl-end">
                                    {{-- Approve Button --}}
                                    <button type="button" 
                                            wire:click="approveReview({{ $selectedReview->id }})"
                                            class="btn btn-success mt-3 me-2"
                                            @if($selectedReview->status) disabled @endif>
                                        <i class="fas fa-check me-2"></i>Approve
                                    </button>
                                    {{-- Reject Button --}}
                                    <button type="button" 
                                            wire:click="rejectReview({{ $selectedReview->id }})"
                                            class="btn btn-danger mt-3"
                                            @if(!$selectedReview->status) disabled @endif>
                                        <i class="fas fa-ban me-2"></i>Reject
                                    </button>
                                </div>
                            </div>
                            
                            <hr class="my-7">
                            
                            <div class="row">
                                <div class="col-md-12 col-lg-4 col-xl-3 mb-8 mb-lg-0">
                                    <article class="border p-6 rounded h-100">
                                        {{-- <p class="mb-0 text-muted">Review ID:</p>
                                        <h5 class="text-success">#{{ $selectedReview->id }}</h5> --}}
                                        
                                        <p class="mb-0 text-muted mt-3">Rating:</p>
                                        <h5 class="text-warning mb-0">{{ $selectedReview->rating }} Stars</h5>
                                    </article>
                                </div>
                                
                                <div class="col-sm-6 col-lg-4 col-xl-5 mb-sm-0 mb-7">
                                    {{-- <h6 class="fs-18px mb-4">
                                        <i class="fas fa-box me-2 text-info"></i>Product Details
                                    </h6> --}}

                                    <!-- Product Image + Name (just like in listing) -->
                                    <div class="d-flex align-items-center mb-4">
                                        <img src="{{ $selectedReview->product->thumbnail_image 
                                            ? asset('storage/' . $selectedReview->product->thumbnail_image) 
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($selectedReview->product->product_name ?? $selectedReview->product->name) . '&background=random&color=fff' }}" 
                                            alt="{{ $selectedReview->product->product_name ?? $selectedReview->product->name }}" 
                                            width="50" 
                                            height="50"
                                            class=" object-fit-cover me-3">

                                        <div>
                                            <p class="fw-semibold mb-0">{{ $selectedReview->product->product_name ?? $selectedReview->product->name }}</p>
                                            <small class="text-muted">ID: #{{ $selectedReview->product->id }}</small>
                                        </div>
                                    </div>

                                    <!-- Rating Stars (same style as table) -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $selectedReview->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="fw-bold">{{ $selectedReview->rating }}/5</span>
                                    </div>

                                    <!-- Review Content -->
                                    <div class="bg-light p-4 rounded-3">
                                        <small class="text-muted d-block mb-2">Review Content</small>
                                        <p class="mb-0 lh-lg">{{ $selectedReview->review }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-lg-4 col-xl-4">
                                    <h6 class="fs-18px mb-4">
                                        <i class="fas fa-history me-2 text-warning"></i>Timeline
                                    </h6>
                                    <p class="mb-3">
                                        <small class="text-muted d-block">Submitted On</small>
                                        <strong>{{ $selectedReview->created_at->format('d M, Y \a\t h:i A') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedReview->created_at->diffForHumans() }})</small>
                                    </p>
                                    
                                    {{-- <p class="mb-0">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <strong>{{ $selectedReview->updated_at->format('d M, Y \a\t h:i A') ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedReview->updated_at->diffForHumans() }})</small>
                                    </p> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0 justify-content-end py-3">
                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>