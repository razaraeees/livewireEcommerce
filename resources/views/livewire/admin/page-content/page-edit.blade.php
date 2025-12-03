<div x-data="{
    title: '',
    slug: '',
    init() {
        this.title = '{{ addslashes($title) }}';
        this.slug = '{{ addslashes($slug) }}';
    },
    generateSlug() {
        this.slug = this.title.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
        $wire.set('slug', this.slug);
    }
}" x-init="$watch('title', value => value && generateSlug())">
    <div class="dashboard-page-content">

        <form wire:submit.prevent="update">
            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Edit Page Content</h2>
                        </div>
                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="update">Update</span>
                                <span wire:loading wire:target="update">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-8 rounded-4">
                        <div class="card-header p-7 bg-transparent">
                            <h4 class="fs-18 mb-0 font-weight-500">Page Content Information</h4>
                        </div>
                        <div class="card-body p-7">
                            <div class="row">

                                <!-- Type -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Type</label>
                                        <select wire:model="type" class="form-control">
                                            <option value="">-- Select Type --</option>
                                            <option value="shipping_info">Shipping Info</option>
                                            <option value="return_policy">Return Policy</option>
                                            <option value="privacy_policy">Privacy Policy</option>
                                            <option value="terms_conditions">Terms & Conditions</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Title</label>
                                        <input type="text" x-model="title" @input="generateSlug()" wire:model="title"
                                            class="form-control" placeholder="Page title">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Slug -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Slug</label>
                                        <input type="text" x-model="slug" wire:model="slug" class="form-control"
                                            placeholder="Auto-generated">
                                        @error('slug')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Auto-generated from title</small>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-lg-6">
                                    <div class="mb-8">
                                        <label class="form-check mb-5">
                                            <input class="form-check-input" type="checkbox" wire:model="status">
                                            <span class="form-check-label">Active</span>
                                        </label>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Content with Quill -->
                                <div class="col-lg-12">
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Content *</label>

                                        <div x-data="{
                                            quill: null,
                                            init() {
                                                this.quill = new Quill('#quill-page-content-editor', {
                                                    theme: 'snow',
                                                    placeholder: 'Write your page content...',
                                                });

                                                // Set initial content
                                                this.quill.root.innerHTML = {{ Js::from($content) }};

                                                this.quill.on('text-change', () => {
                                                    let html = this.quill.root.innerHTML;
                                                    $refs.content.value = html;
                                                    Livewire.dispatch('update-quill-content', {
                                                        model: 'content',
                                                        content: html
                                                    });
                                                });

                                                Livewire.on('reset-quill', () => {
                                                    this.quill.setContents([]);
                                                });
                                            }
                                        }" wire:ignore>
                                            <div id="quill-page-content-editor" style="min-height:250px; background:white;"></div>
                                            <textarea wire:model="content" x-ref="content" style="display:none;"></textarea>
                                            @error('content')
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                            @enderror
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