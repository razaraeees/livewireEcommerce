<div>
    <div class="dashboard-page-content">

        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-sm-6 mb-8 mb-sm-0">
                <h2 class="fs-4 mb-0">Contact Form Submissions</h2>
            </div>
        </div>

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-4 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Search by name, email, phone..." 
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
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body px-0 pt-7 pb-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0 table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle" scope="col">Name</th>
                                <th class="align-middle" scope="col">Email</th>
                                <th class="align-middle" scope="col">Phone</th>
                                <th class="align-middle" scope="col">Subject</th>
                                <th class="align-middle" scope="col">Submitted On</th>
                                <th class="align-middle" scope="col">Status</th>
                                <th class="align-middle text-center" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiry as $contact)
                            <tr wire:key="contact-{{ $contact->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user text-primary me-3 fs-5"></i>
                                        <span class="fw-semibold">{{ $contact->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone ?? '-' }}</td>
                                <td>{{ Str::limit($contact->subject, 30) }}</td>
                                <td>{{ $contact->created_at->format('d M, Y') }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ $contact->status == 'read' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-primary py-4 px-5 btn-xs fs-13px me-4"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#contactDetailModal"
                                            wire:click="viewDetail({{ $contact->id }})">
                                        <i class="fas fa-eye me-2"></i>View Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted mb-0">No contact submissions found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($inquiry->hasPages())
        <nav aria-label="Page navigation example" class="mt-6 mb-4">
            <ul class="pagination justify-content-start">
                @if($inquiry->onFirstPage())
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

                @foreach($inquiry->links()->elements[0] as $page => $url)
                    @if($page == $inquiry->currentPage())
                        <li class="page-item active mx-3" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item mx-3">
                            <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                        </li>
                    @endif
                @endforeach

                @if($inquiry->hasMorePages())
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

    <!-- Contact Detail Modal -->
    @if($selectedInquiries)
    <div class="modal fade" id="contactDetailModal" tabindex="-1" aria-labelledby="contactDetailModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                
                {{-- Card with Brand Header --}}
                <div class="card mb-0 rounded-4">
                    {{-- Golden/Brown Header Banner --}}
                    <div class="card-header p-15" style="background-color: #4e7661; height: 120px;"></div>
                    
                    <div class="card-body p-7">
                        <div class="row">
                            {{-- Profile Image Section (Overlapping Banner) --}}
                            <div class="col-xl col-lg flex-grow-0 mb-xl-0 mb-7" style="flex-basis: 230px">
                                <div class="img-thumbnail shadow w-100 bg-body position-relative text-center mt-n20 py-3 px-4">
                                    <div class="d-flex align-items-center justify-content-center" style="width: 180px; height: 180px;">
                                        <i class="fas fa-envelope text-primary" style="font-size: 80px;"></i>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Name and Info Section --}}
                            <div class="col-xl col-lg">
                                <h3 class="fs-4 mb-0">{{ $selectedInquiries->name ?? '-' }}</h3>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-envelope me-2"></i>{{ $selectedInquiries->email ?? '-' }}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-phone me-2"></i>{{ $selectedInquiries->phone ?? '-' }}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-building me-2"></i>{{ $selectedInquiries->company_name ?? '-' }}
                                </p>
                                <span class="badge rounded-pill {{ $selectedInquiries->status == 'read' ? 'bg-success' : 'bg-warning' }} px-3 py-2">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>{{ ucfirst($selectedInquiries->status ?? 'pending') }}
                                </span>
                            </div>
                            
                            {{-- Actions Section --}}
                            <div class="col-xl-4 text-xl-end">
                                @if($selectedInquiries->status != 'read')
                                <button type="button" 
                                        class="btn btn-success btn-hover-bg-success btn-hover-border-success btn-hover-text-light py-4 px-5 fs-13px btn-xs me-4" 
                                        wire:click="markAsRead({{ $selectedInquiries->id }})"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="markAsRead({{ $selectedInquiries->id }})">
                                        <span class="d-inline-block me-2">Mark as Read</span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span wire:loading wire:target="markAsRead({{ $selectedInquiries->id }})">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Processing...
                                    </span>
                                </button>
                                @endif
                                <button 
                                    x-data 
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
                                                $wire.delete({{ $selectedInquiries->id }})
                                            }
                                        })
                                    "
                                    class="btn btn-outline-primary btn-hover-bg-danger btn-hover-border-danger btn-hover-text-light py-4 px-5 fs-13px btn-xs me-4">
                                    <i class="far fa-trash me-2"></i> Delete
                                </button>
                            </div>
                        </div>
                        
                        <hr class="my-7">
                        
                        {{-- Three Column Layout --}}
                        <div class="row">
                            {{-- Column 1: Contact ID & Status --}}
                            <div class="col-md-12 col-lg-4 col-xl-3 mb-8 mb-lg-0">
                                <article class="border p-6 rounded h-100">
                                    <p class="mb-0 text-muted">Contact ID:</p>
                                    <h5 class="text-success">#{{ $selectedInquiries->id }}</h5>
                                    
                                    <p class="mb-0 text-muted mt-3">Status:</p>
                                    <h5 class="mb-0 text-capitalize {{ $selectedInquiries->status == 'read' ? 'text-success' : 'text-warning' }}">
                                        {{ $selectedInquiries->status }}
                                    </h5>
                                </article>
                            </div>
                            
                            {{-- Column 2: Contact Details --}}
                            <div class="col-sm-6 col-lg-4 col-xl-4 mb-sm-0 mb-7">
                                <h6 class="fs-18px mb-4">
                                    <i class="fas fa-info-circle me-2 text-info"></i>Contact Information
                                </h6>
                                <p class="mb-3">
                                    <small class="text-muted d-block">Full Name</small>
                                    <span class="fw-semibold">{{ $selectedInquiries->name ?? '-' }}</span>
                                </p>
                                
                                <p class="mb-3">
                                    <small class="text-muted d-block">Email Address</small>
                                    <strong>{{ $selectedInquiries->email ?? '-' }}</strong>
                                </p>
                                
                                <p class="mb-3">
                                    <small class="text-muted d-block">Phone Number</small>
                                    <strong>{{ $selectedInquiries->phone ?? '-' }}</strong>
                                </p>
                                
                                <p class="mb-0">
                                    <small class="text-muted d-block">Company Name</small>
                                    <strong>{{ $selectedInquiries->company_name ?? '-' }}</strong>
                                </p>
                            </div>
                            
                            {{-- Column 3: Timeline --}}
                            <div class="col-sm-6 col-lg-4 col-xl-5">
                                <h6 class="fs-18px mb-4">
                                    <i class="fas fa-history me-2 text-warning"></i>Timeline
                                </h6>
                                <p class="mb-3">
                                    <small class="text-muted d-block">Submitted On</small>
                                    @if($selectedInquiries->created_at)
                                        <strong>{{ $selectedInquiries->created_at->format('d M, Y \a\t h:i A') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedInquiries->created_at->diffForHumans() }})</small>
                                    @else
                                        <strong>-</strong>
                                    @endif
                                </p>
                                
                                <p class="mb-0">
                                    <small class="text-muted d-block">Last Updated</small>
                                    @if($selectedInquiries->updated_at)
                                        <strong>{{ $selectedInquiries->updated_at->format('d M, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedInquiries->updated_at->diffForHumans() }})</small>
                                    @else
                                        <strong>-</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <hr class="my-7">
                        
                        {{-- Subject & Message Section --}}
                        <div class="row">
                            <div class="col-12">
                                <h6 class="fs-18px mb-4">
                                    <i class="fas fa-comment-dots me-2 text-primary"></i>Message Details
                                </h6>
                                
                                <div class="bg-light p-4 rounded">
                                    <p class="mb-3">
                                        <small class="text-muted d-block">Subject</small>
                                        <strong class="fs-5">{{ $selectedInquiries->subject ?? '-' }}</strong>
                                    </p>
                                    
                                    <p class="mb-0">
                                        <small class="text-muted d-block mb-2">Message</small>
                                        <span class="d-block" style="white-space: pre-line;">{{ $selectedInquiries->message ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer bg-light border-0 justify-content-end py-3">
                    <button type="button" class="btn btn-danger p-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>