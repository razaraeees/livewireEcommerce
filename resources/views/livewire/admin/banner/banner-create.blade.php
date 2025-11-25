<div>
    <div class="dashboard-page-content">

        <!-- FORM START -->
        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New Banner</h2>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">

                            <!-- Save Draft -->
                            <button type="button" wire:click="saveDraft" class="btn btn-outline-primary me-4" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveDraft">Save to draft</span>
                                <span wire:loading wire:target="saveDraft">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Saving...
                                </span>
                            </button>

                            <!-- Publish -->
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="store">Publish</span>
                                <span wire:loading wire:target="store">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Publishing...
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
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Name</label>
                                                <input type="text" wire:model="title" class="form-control" placeholder="Type here">
                                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Banner Type -->
                                        <div class="col-lg-6">
                                            <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Type</label>
                                            <select class="form-select" wire:model="type">
                                                <option value="">Select Type</option>
                                                <option value="Main Hero Banner">Main Hero Banner</option>
                                                <option value="Middle Banner">Middle Banner</option>
                                                <option value="Annoucement Banner">Annoucement Banner</option>
                                                <option value="Offer Banner">Offer Banner</option>
                                            </select>
                                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Tagline -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Tagline</label>
                                                <input type="text" wire:model="tagline" class="form-control" placeholder="Tagline">
                                                @error('tagline') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Description</label>
                                                <textarea wire:model="description" class="form-control" rows="4"></textarea>
                                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <!-- Banner Link -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Link</label>
                                                <input type="text" wire:model="link" class="form-control">
                                                @error('link') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Banner Alt -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Banner Alt (SEO)</label>
                                                <input type="text" wire:model="alt" class="form-control">
                                                @error('alt') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-lg-4">
                                            <label class="form-check mb-5">
                                                <input class="form-check-input" type="checkbox" wire:model="status">
                                                <span class="form-check-label"> Banner Status </span>
                                            </label>
                                        </div>

                                        <!-- Video Status -->
                                        <div class="col-lg-4">
                                            <label class="form-check mb-5">
                                                <input class="form-check-input" type="checkbox" wire:model="banner_video_status">
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
                                            <div class="position-relative">
                                                <video width="100%" controls class="rounded mb-4">
                                                    <source src="{{ $banner_video->temporaryUrl() }}">
                                                </video>
                                                <button type="button" wire:click="removeVideo" class="btn btn-danger btn-sm position-absolute top-0 end-0">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102" class="d-block mx-auto mb-4">
                                        @endif

                                        <input type="file" wire:model="banner_video" class="form-control">
                                        @error('banner_video') <span class="text-danger">{{ $message }}</span> @enderror

                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- Right Side -->
                        <div class="col-lg-4">

                            <!-- Desktop Banner -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Desktop Banner</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($image)
                                            <div class="position-relative">
                                                <img src="{{ $image->temporaryUrl() }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102" class="d-block mx-auto mb-4">
                                        @endif

                                        <input type="file" wire:model="image" class="form-control">
                                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror

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
                                            <div class="position-relative">
                                                <img src="{{ $mobile_image->temporaryUrl() }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="removeMobileImage" class="btn btn-danger btn-sm position-absolute top-0 end-0">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102" class="d-block mx-auto mb-4">
                                        @endif

                                        <input type="file" wire:model="mobile_image" class="form-control">
                                        @error('mobile_image') <span class="text-danger">{{ $message }}</span> @enderror

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
