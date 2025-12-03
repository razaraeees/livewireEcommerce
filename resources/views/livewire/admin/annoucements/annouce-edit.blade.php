<div class="dashboard-page-content">

    <!-- FORM START -->
    <form wire:submit.prevent="update">

        <div class="row mb-9 align-items-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-6 mb-8 mb-sm-0">
                        <h2 class="fs-4 mb-0">Edit Annoucement</h2>
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
                            <span wire:loading.remove wire:target="update">Publish</span>
                            <span wire:loading wire:target="update">
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
                                <h4 class="fs-18 mb-0 font-weight-500">Annoucement Information</h4>
                            </div>
                            <div class="card-body p-7">
                                <div class="row">

                                    <!-- Title -->
                                    <div class="col-lg-12">
                                        <div class="mb-8">
                                            <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Title
                                                (Optional)</label>
                                            <input type="text" wire:model="title" class="form-control"
                                                placeholder="Enter annoucement title">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Message -->
                                    <div class="col-lg-12">
                                        <div class="mb-8">
                                            <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Message *</label>

                                            <!-- ✅ wire:ignore on x-data container -->
                                            <div x-data="{
                                                quill: null,
                                                init() {
                                                    this.quill = new Quill('#quill-message-editor', {
                                                        theme: 'snow',
                                                        placeholder: 'Write announcement message...',
                                                    });

                                                    // Load existing content from Livewire
                                                    this.quill.root.innerHTML = {{ Js::from($message) }};

                                                    this.quill.on('text-change', () => {
                                                        let html = this.quill.root.innerHTML;
                                                        $refs.message.value = html;
                                                        Livewire.dispatch('update-quill-content', {
                                                            model: 'message',
                                                            content: html
                                                        });
                                                    });

                                                    Livewire.on('reset-quill', () => {
                                                        this.quill.setContents([]);
                                                    });
                                                }
                                            }" wire:ignore>
                                                <div id="quill-message-editor" style="min-height:200px; background:white;"></div>
                                                <textarea wire:model="message" x-ref="message" style="display:none;"></textarea>
                                                @error('message')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Type -->
                                    <div class="col-lg-6">
                                        <div class="mb-8">
                                            <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Type</label>
                                            <select wire:model="type" class="form-control">
                                                <option value="info">Info</option>
                                                <option value="success">Success</option>
                                                <option value="warning">Warning</option>
                                                <option value="danger">Danger</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-lg-6">
                                        <div class="mb-8">
                                            <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Status</label>
                                            <div class="mt-3">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="is_active" checked>
                                                    <span class="form-check-label">Active Annoucement</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="col-lg-4">
                        <!-- Schedule Settings -->
                        <div class="card mb-8 rounded-4">
                            <div class="card-header p-7 bg-transparent">
                                <h4 class="fs-18px mb-0 font-weight-500">Schedule Settings</h4>
                            </div>
                            <div class="card-body p-7">

                                <!-- Start At -->
                                <div class="mb-6">
                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Start Date & Time</label>
                                    <input type="datetime-local" wire:model="start_at" class="form-control">
                                    @error('start_at')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-2">When to start showing this
                                        annoucement</small>
                                </div>

                                <!-- End At -->
                                <div class="mb-6">
                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">End Date & Time</label>
                                    <input type="datetime-local" wire:model="end_at" class="form-control">
                                    @error('end_at')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-2">When to stop showing this annoucement</small>
                                </div>

                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card mb-8 rounded-4 bg-light">
                            <div class="card-body p-7">
                                <h5 class="fs-16 mb-4">📢 Tips</h5>
                                <ul class="mb-0 ps-4">
                                    <li class="mb-2 text-muted fs-14">Title is optional but recommended</li>
                                    <li class="mb-2 text-muted fs-14">Message is required</li>
                                    <li class="mb-2 text-muted fs-14">Schedule dates are optional</li>
                                    <li class="mb-2 text-muted fs-14">Draft will save as inactive</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </form>
    <!-- FORM END -->

</div>
