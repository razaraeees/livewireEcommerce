<div x-data="{
    name: '',
    slug: '',
    generateSlug() {
        this.slug = this.name.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
        $wire.set('url', this.slug);
    }
}" x-init="$watch('name', value => value && generateSlug())">
    <div class="dashboard-page-content">

        <!-- FORM START -->
        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New Category</h2>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <!-- Save Draft -->
                            <button type="button" wire:click="saveDraft" class="btn btn-outline-primary me-4"
                                wire:loading.attr="disabled">
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
                            <!-- Category Information -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">Category Information</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">

                                        <!-- Level Dropdown (First Priority) -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Category Level <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model.live="level" class="form-control" required>
                                                    <option value="">Select Level</option>
                                                    <option value="0">Main Category</option>
                                                    <option value="1">Sub Category</option>
                                                    <option value="2">Sub-Sub Category</option>
                                                </select>
                                                @error('level')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted d-block mt-2">
                                                    <i class="far fa-info-circle"></i>
                                                    @if($level == 0) This will be a main category
                                                    @elseif($level == 1) This will be under a main category
                                                    @elseif($level == 2) This will be under a sub category
                                                    @else Select a level first
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Parent Category (Shows only if Sub or Sub-Sub selected) -->
                                        @if($level > 0)
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Parent Category <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model="parent_id" class="form-control" required>
                                                    <option value="">Select Parent Category</option>
                                                    @if($level == 1)
                                                        {{-- For Sub Category: Show Main Categories (level 0) --}}
                                                        @foreach($mainCategories as $parent)
                                                            <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                                                        @endforeach
                                                    @elseif($level == 2)
                                                        {{-- For Sub-Sub Category: Show Sub Categories (level 1) --}}
                                                        @foreach($subCategories as $parent)
                                                            <option value="{{ $parent->id }}">
                                                                {{ $parent->parent->category_name ?? '' }} → {{ $parent->category_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('parent_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                @if($level == 1 && count($mainCategories) == 0)
                                                    <small class="text-warning d-block mt-2">
                                                        <i class="far fa-exclamation-triangle"></i> No main categories available. Please create a main category first.
                                                    </small>
                                                @elseif($level == 2 && count($subCategories) == 0)
                                                    <small class="text-warning d-block mt-2">
                                                        <i class="far fa-exclamation-triangle"></i> No sub categories available. Please create a sub category first.
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Category Name -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Category Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" x-model="name" @input="generateSlug()"
                                                    wire:model="category_name" class="form-control" placeholder="Type here" required>
                                                @error('category_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- URL/Slug -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    URL/Slug <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" x-model="slug" wire:model="url"
                                                    class="form-control" placeholder="Auto-generated slug" required>
                                                @error('url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Slug will be auto-generated from category name</small>
                                            </div>
                                        </div>

                                        <!-- Discount -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Category Discount (%)</label>
                                                <input type="number" wire:model="category_discount" class="form-control" placeholder="0" min="0" max="100" step="0.01">
                                                @error('category_discount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Status Checkbox -->
                                        <div class="col-lg-6">
                                            <div class="mb-8 pt-8">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" wire:model="status" checked>
                                                    <span class="form-check-label">
                                                        <i class="far fa-check-circle text-success"></i> Category Status (Active)
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Description</label>
                                                <textarea wire:model="description" class="form-control" rows="4" placeholder="Enter category description..."></textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- SEO Information -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">
                                        <i class="far fa-search me-2"></i> SEO Information
                                    </h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">

                                        <!-- Meta Title -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta Title</label>
                                                <input type="text" wire:model="meta_title" class="form-control" placeholder="SEO Title">
                                                @error('meta_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Meta Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta Description</label>
                                                <textarea wire:model="meta_description" class="form-control" rows="3" placeholder="SEO Description"></textarea>
                                                @error('meta_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Meta Keywords -->
                                        <div class="col-lg-12">
                                            <div class="mb-0">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta Keywords</label>
                                                <input type="text" wire:model="meta_keywords" class="form-control" placeholder="keyword1, keyword2, keyword3">
                                                @error('meta_keywords')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Separate keywords with commas</small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="col-lg-4">
                            <!-- Category Image -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">
                                        <i class="far fa-image me-2"></i> Category Image
                                    </h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($category_image)
                                            <div class="position-relative">
                                                <img src="{{ $category_image->temporaryUrl() }}" class="w-100 rounded mb-4" style="max-height: 250px; object-fit: cover;">
                                                <button type="button" wire:click="$set('category_image', null)"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102"
                                                class="d-block mx-auto mb-4">
                                            <p class="text-muted">No image uploaded</p>
                                        @endif

                                        <input type="file" wire:model="category_image"
                                            class="form-control @error('category_image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB | JPG, PNG, WEBP</small>
                                        @error('category_image')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="category_image" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Category Banner -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">
                                        <i class="far fa-panorama me-2"></i> Category Banner
                                    </h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">

                                        @if ($category_banner)
                                            <div class="position-relative">
                                                <img src="{{ $category_banner->temporaryUrl() }}" class="w-100 rounded mb-4" style="max-height: 250px; object-fit: cover;">
                                                <button type="button" wire:click="$set('category_banner', null)"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102"
                                                class="d-block mx-auto mb-4">
                                            <p class="text-muted">No banner uploaded</p>
                                        @endif

                                        <input type="file" wire:model="category_banner"
                                            class="form-control @error('category_banner') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB | Recommended: 1920x500</small>
                                        @error('category_banner')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="category_banner" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>
                                    </div>

                                    <!-- Banner Status -->
                                    <div class="mt-4">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="banner_status">
                                            <span class="form-check-label">
                                                <i class="far fa-eye text-primary"></i> Show Banner on Frontend
                                            </span>
                                        </label>
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