<div>
    <div class="dashboard-page-content">
        <!-- FORM START -->
        <form wire:submit.prevent="store">
            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New Variant</h2>
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
                            @foreach($variantList as $index => $variant)
                                <div class="card mb-8 rounded-4" wire:key="variant-{{ $index }}" 
                                    x-data="{
                                        name: '',
                                        slug: '',
                                        generateSlug() {
                                            this.slug = this.name.toLowerCase()
                                                .replace(/ /g, '-')
                                                .replace(/[^\w-]+/g, '');
                                            $wire.set('variantList.{{ $index }}.slug', this.slug);
                                            $wire.checkSlugAvailability({{ $index }}, this.slug);
                                        }
                                    }" 
                                    x-init="$watch('name', value => value && generateSlug())">

                                    <div class="card-header p-7 bg-transparent d-flex justify-content-between align-items-center">
                                        <h4 class="fs-18 mb-0 font-weight-500">Variant Option</h4>
                                        @if($index > 0)
                                            <button type="button" wire:click="removeVariantField({{ $index }})"
                                                class="btn btn-danger btn-sm">
                                                <i class="far fa-times"></i> Remove
                                            </button>
                                        @endif
                                    </div>

                                    <div class="card-body p-7">
                                        <div class="row">
                                            <!-- Variant Name -->
                                            <div class="col-lg-6">
                                                <div class="mb-8">
                                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                        Variant Name
                                                    </label>
                                                    <input type="text" 
                                                        x-model="name"
                                                        @input="generateSlug()"
                                                        wire:model="variantList.{{ $index }}.name"
                                                        class="form-control" 
                                                        placeholder="e.g., Size, Color, Material">
                                                    @error('variantList.'.$index.'.name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Slug with Live Validation -->
                                            <div class="col-lg-6">
                                                <div class="mb-8">
                                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Slug</label>
                                                    <div class="position-relative">
                                                        <input type="text" 
                                                            x-model="slug"
                                                            wire:model.live.debounce.500ms="variantList.{{ $index }}.slug"
                                                            class="form-control @error('variantList.'.$index.'.slug') is-invalid @enderror
                                                                   @if(isset($slugAvailability[$index]) && $slugAvailability[$index] === false) is-invalid @endif
                                                                   @if(isset($slugAvailability[$index]) && $slugAvailability[$index] === true) is-valid @endif" 
                                                            placeholder="auto-generated-slug">
                                                        
                                                        <!-- Loading Spinner -->
                                                        <div wire:loading wire:target="checkSlugAvailability" 
                                                            class="position-absolute" 
                                                            style="right: 10px; top: 50%; transform: translateY(-50%);">
                                                            <span class="spinner-border spinner-border-sm text-primary"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Validation Messages -->
                                                    @error('variantList.'.$index.'.slug')
                                                        <span class="text-danger small d-block mt-1">
                                                            <i class="far fa-exclamation-circle me-1"></i>{{ $message }}
                                                        </span>
                                                    @enderror

                                                    @if(isset($slugAvailability[$index]))
                                                        @if($slugAvailability[$index] === false)
                                                            <span class="text-danger small d-block mt-1">
                                                                <i class="far fa-times-circle me-1"></i>This slug is already taken
                                                            </span>
                                                        @elseif($slugAvailability[$index] === true)
                                                            <span class="text-success small d-block mt-1">
                                                                <i class="far fa-check-circle me-1"></i>Slug is available
                                                            </span>
                                                        @endif
                                                    @endif

                                                    <small class="text-muted d-block mt-1">Slug will be auto-generated from variant name</small>
                                                </div>
                                            </div>

                                            <!-- Variant Values -->
                                            <div class="col-lg-12">
                                                <div class="mb-8">
                                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                        Variant Values
                                                    </label>

                                                    <div x-data="{ inputValue: '' }">
                                                        <div class="tag-container" 
                                                            style="display: flex; flex-wrap: wrap; gap: 8px; padding: 12px; border: 1px solid #dee2e6; border-radius: 0.375rem; min-height: 50px; background: #fff; cursor: text;"
                                                            @click="$refs.tagInput{{ $index }}.focus()">

                                                            @if(isset($variantList[$index]['values']) && count($variantList[$index]['values']) > 0)
                                                                @foreach($variantList[$index]['values'] as $valueIndex => $value)
                                                                    <span class="tag-item" 
                                                                        style="display: inline-flex; align-items: center; padding: 6px 14px; background: #0d6efd; color: white; border-radius: 20px; font-size: 14px; gap: 8px;"
                                                                        wire:key="value-{{ $index }}-{{ $valueIndex }}">
                                                                        {{ $value }}
                                                                        <span wire:click="removeValue({{ $index }}, {{ $valueIndex }})" 
                                                                            style="cursor: pointer; font-weight: bold; opacity: 0.8; font-size: 18px; line-height: 1;">&times;</span>
                                                                    </span>
                                                                @endforeach
                                                            @endif

                                                            <input type="text" 
                                                                x-model="inputValue"
                                                                @keydown.enter.prevent="if(inputValue.trim()) { $wire.addValue({{ $index }}, inputValue.trim()); inputValue = ''; }"
                                                                @keydown.comma.prevent="if(inputValue.trim()) { $wire.addValue({{ $index }}, inputValue.trim()); inputValue = ''; }"
                                                                x-ref="tagInput{{ $index }}"
                                                                style="border: none; outline: none; background: transparent; flex: 1; min-width: 150px; padding: 5px; font-size: 14px;"
                                                                placeholder="Type and press Enter or comma">
                                                        </div>

                                                        <small class="text-muted d-block mt-2">
                                                            <i class="far fa-info-circle me-1"></i>Type a value and press <strong>Enter</strong> or <strong>comma (,)</strong>
                                                        </small>
                                                    </div>

                                                    @error('variantList.'.$index.'.values')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Add More Variant -->
                            <button type="button" wire:click="addVariantField" class="btn btn-outline-primary w-100 mb-4">
                                <i class="far fa-plus me-2"></i>Add More Variant
                            </button>
                        </div>

                        <!-- Right Side -->
                        <div class="col-lg-4">
                            <!-- Settings -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Settings</h4>
                                </div>
                                <div class="card-body p-7">

                                    <div class="mb-4">
                                        <label class="form-check mb-5">
                                            <input class="form-check-input" type="checkbox" wire:model="status" checked>
                                            <span class="form-check-label"> Variant Status </span>
                                        </label>
                                    </div>

                                    <div class="alert alert-info mb-0" role="alert">
                                        <strong><i class="far fa-lightbulb me-1"></i> Tip:</strong>
                                        <p class="mb-0 small">You can add multiple variants. Each variant can have multiple values.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="card rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Summary</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Total Variants:</span>
                                        <strong>{{ count($variantList) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Status:</span>
                                        <span class="badge bg-{{ $status ? 'success' : 'secondary' }}">
                                            {{ $status ? 'Active' : 'Draft' }}
                                        </span>
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