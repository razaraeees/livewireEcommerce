<div>
    <div class="dashboard-page-content">

        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-sm-6 mb-8 mb-sm-0">
                <h2 class="fs-4 mb-0">Newsletter Subscribers</h2>
            </div>
            {{-- <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                <a href="#" class="btn btn-primary">
                    <i class="far fa-envelope"></i>
                    <span class="d-inline-block ml-2">Export Subscribers</span>
                </a>
            </div> --}}
        </div>

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-4 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Search by email..." 
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
                                <th class="align-middle" scope="col">Email</th>
                                <th class="align-middle" scope="col">Subscribed On</th>
                                <th class="align-middle" scope="col">Status</th>
                                {{-- <th class="align-middle text-center" scope="col">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscribe as $subscriber)
                            <tr wire:key="subscriber-{{ $subscriber->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope-open text-primary me-3 fs-5"></i>
                                        <span class="fw-semibold">{{ $subscriber->email }}</span>
                                    </div>
                                </td>
                                <td>{{ $subscriber->created_at->format('d M, Y') }}</td>
                                <td>{{ $subscriber->status }}</td>
                                {{-- <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-sm btn-primary rounded-pill px-4"
                                            wire:click="viewDetail({{ $subscriber->id }})"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#subscriberDetailModal">
                                        <i class="fas fa-eye me-2"></i>View Detail
                                    </button>
                                </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <p class="text-muted mb-0">No subscribers found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($subscribe->hasPages())
        <nav aria-label="Page navigation example" class="mt-6 mb-4">
            <ul class="pagination justify-content-start">
                @if($subscribe->onFirstPage())
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

                @foreach($subscribe->links()->elements[0] as $page => $url)
                    @if($page == $subscribe->currentPage())
                        <li class="page-item active mx-3" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item mx-3">
                            <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                        </li>
                    @endif
                @endforeach

                @if($subscribe->hasMorePages())
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

    <!-- Subscriber Detail Modal -->
    @if($selectedSubscriber)
        <div class="modal fade" id="subscriberDetailModal" tabindex="-1" aria-labelledby="subscriberDetailModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    
                    {{-- Card with Brand Header --}}
                    <div class="card mb-0 rounded-4">
                        {{-- Golden/Brown Header Banner --}}
                        <div class="card-header p-15" style="background-color: #B88539; height: 120px;"></div>
                        
                        <div class="card-body p-7">
                            <div class="row">
                                {{-- Profile Image Section (Overlapping Banner) --}}
                                <div class="col-xl col-lg flex-grow-0 mb-xl-0 mb-7" style="flex-basis: 230px">
                                    <div class="img-thumbnail shadow w-100 bg-body position-relative text-center mt-n20 py-3 px-4">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 180px; height: 180px;">
                                            <i class="fas fa-envelope-open-text text-primary" style="font-size: 80px;"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Email and Info Section --}}
                                <div class="col-xl col-lg">
                                    <h3 class="fs-4 mb-0">Newsletter Subscriber</h3>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-envelope me-2"></i>{{ $selectedSubscriber->email }}
                                    </p>
                                    <span class="badge rounded-pill {{ $selectedSubscriber->status ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} px-3 py-1">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i>{{ $selectedSubscriber->status ? 'Active' : 'Pending' }}
                                    </span>
                                </div>
                                
                                {{-- Actions Section --}}
                                <div class="col-xl-4 text-xl-end">
                                    <button type="button" class="btn btn-success mt-3 me-2">
                                        <span class="d-inline-block me-2">Send Email</span>
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger mt-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <hr class="my-7">
                            
                            {{-- Three Column Layout --}}
                            <div class="row">
                                {{-- Column 1: Subscriber ID & Status --}}
                                <div class="col-md-12 col-lg-4 col-xl-3 mb-8 mb-lg-0">
                                    <article class="border p-6 rounded h-100">
                                        <p class="mb-0 text-muted">Subscriber ID:</p>
                                        <h5 class="text-success">#{{ $selectedSubscriber->id }}</h5>
                                        
                                        <p class="mb-0 text-muted mt-3">Status:</p>
                                        <h5 class="mb-0 text-capitalize {{ $selectedSubscriber->status ? 'text-success' : 'text-warning' }}">
                                            {{ $selectedSubscriber->status ? 'Active' : 'Pending' }}
                                        </h5>
                                    </article>
                                </div>
                                
                                {{-- Column 2: Email Details --}}
                                <div class="col-sm-6 col-lg-4 col-xl-4 mb-sm-0 mb-7">
                                    <h6 class="fs-18px mb-4">
                                        <i class="fas fa-info-circle me-2 text-info"></i>Email Information
                                    </h6>
                                    <p class="mb-3">
                                        <small class="text-muted d-block">Email Address</small>
                                        <span class="fw-semibold">{{ $selectedSubscriber->email }}</span>
                                    </p>
                                    
                                    <p class="mb-0">
                                        <small class="text-muted d-block">Domain</small>
                                        <strong>{{ explode('@', $selectedSubscriber->email)[1] ?? 'N/A' }}</strong>
                                    </p>
                                </div>
                                
                                {{-- Column 3: Timeline --}}
                                <div class="col-sm-6 col-lg-4 col-xl-5">
                                    <h6 class="fs-18px mb-4">
                                        <i class="fas fa-history me-2 text-warning"></i>Timeline
                                    </h6>
                                    <p class="mb-3">
                                        <small class="text-muted d-block">Subscribed On</small>
                                        <strong>{{ $selectedSubscriber->created_at->format('d M, Y \a\t h:i A') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedSubscriber->created_at->diffForHumans() }})</small>
                                    </p>
                                    
                                    <p class="mb-0">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <strong>{{ $selectedSubscriber->updated_at->format('d M, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedSubscriber->updated_at->diffForHumans() }})</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer bg-light border-0 justify-content-center py-3">
                        <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>