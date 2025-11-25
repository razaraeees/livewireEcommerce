<!-- BLADE VIEW: banner-edit.blade.php -->
<div>
    <div class="dashboard-page-content">

        <!-- FORM START -->
        <form wire:submit.prevent="update">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Edit Banner</h2>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <!-- Update Button -->
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="update">Update Banner</span>
                                <span wire:loading wire:target="update">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MAIN CONTENT -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="row">
                        <!-- Left Side -->
                        <div class="col-lg-8">
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">Banner Information</h4>
                                </div>
                                <div class="card-body p-7">

                                    <div class="row">

                                        <!-- Banner Title -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Name <span class="text-danger">*</span></label>
                                                <input type="text" wire:model.defer="title" class="form-control @error('title') is-invalid @enderror" placeholder="Type here">
                                                @error('title') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Banner Type -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Type <span class="text-danger">*</span></label>
                                                <select class="form-select @error('type') is-invalid @enderror" wire:model.defer="type">
                                                    <option value="">Select Type</option>
                                                    <option value="Main Hero Banner">Main Hero Banner</option>
                                                    <option value="Middle Banner">Middle Banner</option>
                                                    <option value="Annoucement Banner">Annoucement Banner</option>
                                                    <option value="Offer Banner">Offer Banner</option>
                                                </select>
                                                @error('type') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Tagline -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Tagline</label>
                                                <input type="text" wire:model.defer="tagline" class="form-control @error('tagline') is-invalid @enderror" placeholder="Tagline">
                                                @error('tagline') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Description</label>
                                                <textarea wire:model.defer="description" class="form-control @error('description') is-invalid @enderror" rows="4"></textarea>
                                                @error('description') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <!-- Banner Link -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Link</label>
                                                <input type="text" wire:model.defer="link" class="form-control @error('link') is-invalid @enderror" placeholder="https://example.com">
                                                @error('link') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Banner Alt -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Alt (SEO) <span class="text-danger">*</span></label>
                                                <input type="text" wire:model.defer="alt" class="form-control @error('alt') is-invalid @enderror" placeholder="Image description">
                                                @error('alt') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-lg-6">
                                            <label class="form-check mb-5">
                                                <input class="form-check-input" type="checkbox" wire:model.defer="status" value="1">
                                                <span class="form-check-label"> Banner Active </span>
                                            </label>
                                        </div>

                                        <!-- Video Status -->
                                        <div class="col-lg-6">
                                            <label class="form-check mb-5">
                                                <input class="form-check-input" type="checkbox" wire:model.defer="banner_video_status" value="1">
                                                <span class="form-check-label"> Banner Video Active </span>
                                            </label>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <!-- Banner Video -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Banner Video</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($banner_video)
                                            <!-- New Video Preview -->
                                            <div class="position-relative">
                                                <video width="100%" controls class="rounded mb-4">
                                                    <source src="{{ $banner_video->temporaryUrl() }}">
                                                </video>
                                                <button type="button" wire:click="removeVideo" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @elseif($old_banner_video)
                                            <!-- Existing Video with Delete Button -->
                                            <div class="position-relative mb-3">
                                                <video width="100%" controls class="rounded mb-2">
                                                    <source src="{{ asset('storage/' . $old_banner_video) }}" type="video/mp4">
                                                </video>
                                                <button type="button" 
                                                        wire:click="deleteExistingVideo" 
                                                        wire:confirm="Are you sure you want to delete this video?"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">
                                                    &times;
                                                </button>
                                                <p class="text-muted small mb-0 mt-2">Current Video</p>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102" class="d-block mx-auto mb-4">
                                            <p class="text-muted">No video uploaded</p>
                                        @endif

                                        <input type="file" wire:model="banner_video" class="form-control @error('banner_video') is-invalid @enderror" accept="video/mp4,video/mov,video/avi">
                                        <small class="text-muted d-block mt-2">Max size: 50MB (mp4, mov, avi)</small>
                                        @error('banner_video') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="banner_video,deleteExistingVideo" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- Right Side -->
                        <div class="col-lg-4">

                            <!-- Desktop Banner -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Desktop Banner <span class="text-danger">*</span></h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($image)
                                            <!-- New Image Preview -->
                                            <div class="position-relative">
                                                <img src="{{ $image->temporaryUrl() }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @elseif($old_image)
                                            <!-- Existing Image with Delete Button -->
                                            <div class="position-relative mb-3">
                                                <img src="{{ asset('storage/' . $old_image) }}" class="w-100 rounded mb-2">
                                                <button type="button" 
                                                        wire:click="deleteExistingImage" 
                                                        wire:confirm="Are you sure you want to delete this image?"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">
                                                    &times;
                                                </button>
                                                <p class="text-muted small mb-0 mt-2">Current Image</p>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102" class="d-block mx-auto mb-4">
                                            <p class="text-muted">No image uploaded</p>
                                        @endif

                                        <input type="file" wire:model="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB</small>
                                        @error('image') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="image,deleteExistingImage" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Banner -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Mobile Banner</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($mobile_image)
                                            <!-- New Mobile Image Preview -->
                                            <div class="position-relative">
                                                <img src="{{ $mobile_image->temporaryUrl() }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="removeMobileImage" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @elseif($old_mobile_image)
                                            <!-- Existing Mobile Image with Delete Button -->
                                            <div class="position-relative mb-3">
                                                <img src="{{ asset('storage/' . $old_mobile_image) }}" class="w-100 rounded mb-2">
                                                <button type="button" 
                                                        wire:click="deleteExistingMobileImage" 
                                                        wire:confirm="Are you sure you want to delete this mobile image?"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">
                                                    &times;
                                                </button>
                                                <p class="text-muted small mb-0 mt-2">Current Mobile Image</p>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102" class="d-block mx-auto mb-4">
                                            <p class="text-muted">No mobile image uploaded</p>
                                        @endif

                                        <input type="file" wire:model="mobile_image" class="form-control @error('mobile_image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB</small>
                                        @error('mobile_image') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="mobile_image,deleteExistingMobileImage" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </form>
        <!-- FORM END -->

    </div>
</div>

