<div>
    <div class="dashboard-page-content">

        <div class="row mb-9 align-items-center justify-content-between">
            <div class="col-sm-6 mb-8 mb-sm-0">
                <h2 class="fs-4 mb-0">Customer List</h2>
            </div>
            <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                <a href="#" class="btn btn-primary">
                    <i class="far fa-user"></i>
                    <span class="d-inline-block ml-2">Import User List</span>
                </a>
            </div>
        </div>

        <div class="card mb-4 rounded-4 p-7">
            <div class="card-header bg-transparent px-0 pt-0 pb-7">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-4 col-12 mr-auto mb-md-0 mb-6">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Search..." 
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
                                    <option value="all">Status: All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
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
                                <th class="align-middle" scope="col">Image</th>
                                <th class="align-middle" scope="col">Name</th>
                                <th class="align-middle" scope="col">Email</th>
                                <th class="align-middle" scope="col">Registered</th>
                                <th class="align-middle" scope="col">Status</th>
                                <th class="align-middle text-center" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerUser as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td>
                                    <img src="{{ $user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4e7661&color=fff' }}" 
                                         alt="{{ $user->name }}" 
                                         width="60" 
                                         height="60"
                                         class="rounded-pill object-fit-cover">
                                </td>
                                <td>
                                    <div>
                                        <a href="#" class="text-dark fw-semibold">{{ $user->name }}</a>
                                        <span class="d-block fs-13px text-muted">ID#{{ $user->id }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               role="switch" 
                                               id="status-{{ $user->id }}"
                                               wire:click="toggleStatus({{ $user->id }})"
                                               @if($user->user_status) checked @endif>
                                        <label class="form-check-label ms-3 fw-semibold {{ $user->user_status ? 'text-success' : 'text-muted' }}" 
                                               for="status-{{ $user->id }}">
                                            {{ $user->user_status ? 'Active' : 'Inactive' }}
                                        </label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-primary fs-13px btn-xs py-4" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#userDetailModal"
                                            wire:click="viewUserDetails({{ $user->id }})">
                                        <i class="far fa-eye me-2"></i>View details
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted mb-0">No customers found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($customerUser->hasPages())
        <nav aria-label="Page navigation example" class="mt-6 mb-4">
            <ul class="pagination justify-content-start">
                @if($customerUser->onFirstPage())
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

                @foreach($customerUser->links()->elements[0] as $page => $url)
                    @if($page == $customerUser->currentPage())
                        <li class="page-item active mx-3" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item mx-3">
                            <button type="button" class="page-link" wire:click="gotoPage({{ $page }})">{{ $page }}</button>
                        </li>
                    @endif
                @endforeach

                @if($customerUser->hasMorePages())
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

    <!-- User Detail Modal -->
    @if($selectedUser)
        <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true" wire:ignore.self>
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
                                        <img class="img-fluid rounded-3" 
                                            src="{{ $selectedUser->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedUser->name) . '&background=4e7661&color=fff&size=180&font-size=0.35' }}" 
                                            alt="{{ $selectedUser->name }}" 
                                            width="180" 
                                            height="180"
                                            style="object-fit: cover;">
                                    </div>
                                </div>
                                
                                {{-- Name and Email Section --}}
                                <div class="col-xl col-lg">
                                    <h3 class="fs-4 mb-0">{{ $selectedUser->name }}</h3>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-envelope me-2"></i>{{ $selectedUser->email }}
                                    </p>
                                    <span class="badge rounded-pill {{ $selectedUser->user_status ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-1">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i>{{ $selectedUser->user_status ? 'Active Account' : 'Inactive Account' }}
                                    </span>
                                </div>
                                
                                {{-- Actions Section --}}
                                <div class="col-xl-4 text-xl-end">
                                    <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">
                                        <span class="d-inline-block me-2">View Profile</span>
                                        <i class="fas fa-external-link-alt"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <hr class="my-7">
                            
                            {{-- Three Column Layout --}}
                            <div class="row">
                                {{-- Column 1: User ID & Role (Sales Style) --}}
                                <div class="col-md-12 col-lg-4 col-xl-2 mb-8 mb-lg-0">
                                    <article class="border p-6 rounded h-100">
                                        <p class="mb-0 text-muted">User ID:</p>
                                        <h5 class="text-success">#{{ $selectedUser->id }}</h5>
                                        
                                        <p class="mb-0 text-muted mt-3">Role:</p>
                                        <h5 class="text-success mb-0 text-capitalize">{{ $selectedUser->role ?? 'Customer' }}</h5>
                                        
                                        @if(isset($selectedUser->dob))
                                        <p class="mb-0 text-muted mt-3">Age:</p>
                                        <h5 class="text-dark mb-0">{{ \Carbon\Carbon::parse($selectedUser->dob)->age }} yrs</h5>
                                        @endif
                                    </article>
                                </div>
                                
                                {{-- Column 2: Contact & Verification --}}
                                <div class="col-sm-6 col-lg-4 col-xl-3 mb-sm-0 mb-7">
                                    <h6 class="fs-18px mb-4">
                                        <i class="fas fa-address-book me-2 text-info"></i>Contact & Verification
                                    </h6>
                                    <p class="mb-3">
                                        <small class="text-muted d-block">Email Verification</small>
                                        @if($selectedUser->email_verified_at)
                                        <span class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>Verified
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $selectedUser->email_verified_at->format('d M, Y') }}</small>
                                        @else
                                        <span class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Not Verified
                                        </span>
                                        @endif
                                    </p>
                                    
                                    @if(isset($selectedUser->dob))
                                    <p class="mb-0">
                                        <small class="text-muted d-block">Date of Birth</small>
                                        <strong>{{ \Carbon\Carbon::parse($selectedUser->dob)->format('d M, Y') }}</strong>
                                    </p>
                                    @endif
                                </div>
                                
                                {{-- Column 3: Timeline --}}
                                <div class="col-sm-6 col-lg-4 col-xl-3">
                                    <h6 class="fs-18px mb-4">
                                        <i class="fas fa-history me-2 text-warning"></i>Timeline
                                    </h6>
                                    <p class="mb-3">
                                        <small class="text-muted d-block">Registered On</small>
                                        <strong>{{ $selectedUser->created_at->format('d M, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedUser->created_at->diffForHumans() }})</small>
                                    </p>
                                    
                                    <p class="mb-0">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <strong>{{ $selectedUser->updated_at->format('d M, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">({{ $selectedUser->updated_at->diffForHumans() }})</small>
                                    </p>
                                </div>
                                
                                {{-- Column 4: Location/Address (if available) --}}
                                <div class="col-sm-6 col-xl-4 text-xl-right d-flex align-items-center mt-xl-0 mt-7 justify-content-xl-end">
                                    @if(isset($selectedUser->address) || isset($selectedUser->city))
                                    <div class="w-100">
                                        <h6 class="fs-18px mb-4">
                                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>Location
                                        </h6>
                                        <div class="bg-light p-4 rounded border">
                                            <p class="mb-0">
                                                <small class="text-muted d-block">Address</small>
                                                <strong>{{ $selectedUser->address ?? 'N/A' }}</strong>
                                            </p>
                                            <p class="mb-0 mt-2">
                                                <small class="text-muted d-block">City</small>
                                                <strong>{{ $selectedUser->city ?? 'N/A' }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                    @endif
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

