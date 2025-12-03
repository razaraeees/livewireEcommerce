<div x-data="{
    pageName: '',
    generateSlug() {
        if (!this.pageName) {
            $wire.set('page_url', '', true);
            return;
        }
        let slug = this.pageName.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '')
            .replace(/-+/g, '-');
        $wire.set('page_url', slug, true);

        // Optional: Auto-fill OG URL if empty
        if (!$wire.get('og_url')) {
            $wire.set('og_url', '{{ url('/') }}/' + slug, true);
        }
    }
}"
x-init="$watch('pageName', () => generateSlug())">

    <div class="dashboard-page-content">
        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New SEO Page</h2>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <button type="button" wire:click="saveDraft" class="btn btn-outline-primary me-4"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveDraft">Save Draft</span>
                                <span wire:loading wire:target="saveDraft">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Saving...
                                </span>
                            </button>

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

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Left Side -->
                        <div class="col-lg-8">
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">Page Information</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">
                                        <!-- Page Name -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Page Name</label>
                                                <input type="text" 
                                                    x-model="pageName"
                                                    @input="generateSlug()"
                                                    wire:model="page_name" 
                                                    class="form-control" 
                                                    placeholder="e.g. About Us">
                                                @error('page_name') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Page URL (Slug) -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Page URL</label>
                                                <input type="text" 
                                                    wire:model="page_url" 
                                                    class="form-control" 
                                                    placeholder="Auto-generated">
                                                @error('page_url') <span class="text-danger">{{ $message }}</span> @enderror
                                                <small class="text-muted">Will be used in URL (e.g. /about-us)</small>
                                            </div>
                                        </div>

                                        <!-- Meta Title -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta Title</label>
                                                <input type="text" wire:model="meta_title" class="form-control"
                                                    placeholder="SEO title for this page">
                                                @error('meta_title') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Meta Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta Description</label>
                                                <textarea wire:model="meta_description" class="form-control" rows="3"
                                                    placeholder="Brief description for search engines"></textarea>
                                                @error('meta_description') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Meta Keywords -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta Keywords</label>
                                                <input type="text" wire:model="meta_keywords" class="form-control"
                                                    placeholder="comma, separated, keywords">
                                                @error('meta_keywords') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-lg-4">
                                            <label class="form-check mb-5">
                                                <input class="form-check-input" type="checkbox" wire:model="status" checked>
                                                <span class="form-check-label">Page Status (Active)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- OG Section -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">Open Graph (Social Media)</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-6">
                                                <label class="mb-2">OG Title</label>
                                                <input type="text" wire:model="og_title" class="form-control">
                                                @error('og_title') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-6">
                                                <label class="mb-2">OG Type</label>
                                                <input type="text" wire:model="og_type" class="form-control" value="website">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-6">
                                                <label class="mb-2">OG Description</label>
                                                <textarea wire:model="og_description" class="form-control" rows="2"></textarea>
                                                @error('og_description') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-6">
                                                <label class="mb-2">OG URL (optional)</label>
                                                <input type="text" 
                                                    wire:model="og_url" 
                                                    class="form-control" 
                                                    placeholder="e.g. {{ url('/') }}/{{ $page_url ?? 'your-page' }}">
                                                @error('og_url') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Meta Image -->
                        <div class="col-lg-4">
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Meta Image</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">
                                        @if ($meta_image)
                                            <div class="position-relative">
                                                <img src="{{ $meta_image->temporaryUrl() }}" class="w-100 rounded mb-4">
                                                <button type="button" wire:click="removeImage"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102"
                                                class="d-block mx-auto mb-4">
                                            <p class="text-muted">Upload social/share image</p>
                                        @endif

                                        <input type="file" wire:model="meta_image"
                                            class="form-control @error('meta_image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Recommended: 1200×630px, Max 2MB</small>
                                        @error('meta_image')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="meta_image" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                            <span class="ms-2 text-muted">Uploading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>