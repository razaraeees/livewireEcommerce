<div x-data="{
    name: @entangle('name'),
    slug: @entangle('slug'),
    generateSlug() {
        if (this.name) {
            this.slug = this.name.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w-]+/g, '');
        }
    }
}" x-init="$watch('name', value => value && generateSlug())">
    <div class="dashboard-page-content">

        <!-- FORM START -->
        <form wire:submit.prevent="update">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Edit Brand</h2>
                            <p class="text-muted mb-0">Editing: {{ $brand->name ?? '' }}</p>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <!-- Save Draft -->
                            <button type="button" wire:click="saveDraft" class="btn btn-outline-primary me-4"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveDraft">Save as draft</span>
                                <span wire:loading wire:target="saveDraft">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Saving...
                                </span>
                            </button>

                            <!-- Update -->
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="update">Update Brand</span>
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
                                    <h4 class="fs-18 mb-0 font-weight-500">Brand Information</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">

                                        <!-- Brand Name -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Brand Name</label>
                                                <input type="text" x-model="name" @input="generateSlug()"
                                                    wire:model="name" class="form-control" placeholder="Type here">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Slug -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Slug</label>
                                                <input type="text" x-model="slug" wire:model="slug"
                                                    class="form-control" placeholder="Auto-generated slug">
                                                @error('slug')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Slug will be auto-generated from brand name</small>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Description</label>
                                                <textarea wire:model="description" class="form-control" rows="4" placeholder="Brand description">{{ $description }}</textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-lg-4">
                                            <label class="form-check mb-5">
                                                <input class="form-check-input" type="checkbox" wire:model="status"
                                                    {{ $status ? 'checked' : '' }}>
                                                <span class="form-check-label"> Brand Status </span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="col-lg-4">
                            <!-- Brand Image -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Brand Image</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($image)
                                            <!-- New Image Preview -->
                                            <div class="position-relative">
                                                <img src="{{ $image->temporaryUrl() }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="removeImage"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @elseif ($oldImage)
                                            <!-- Existing Image Preview with Delete Button -->
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $oldImage) }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="deleteExistingImage"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                                                    wire:loading.attr="disabled"
                                                    title="Delete this image">
                                                    &times;
                                                    <span wire:loading wire:target="deleteExistingImage" 
                                                          class="spinner-border spinner-border-sm"></span>
                                                </button>
                                                <small class="text-muted d-block">Current image - Click × to delete</small>
                                            </div>
                                        @else
                                            <!-- No Image -->
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102"
                                                class="d-block mx-auto mb-4">
                                            <p class="text-muted">No image uploaded</p>
                                        @endif

                                        <input type="file" wire:model="image"
                                            class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB</small>
                                        @error('image')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="image" class="mt-3">
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