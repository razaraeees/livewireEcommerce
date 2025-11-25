<div>
    <div class="dashboard-page-content">
        <!-- FORM START -->
        <form wire:submit.prevent="store">
            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New Attribute</h2>
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
                            @foreach($attributeList as $index => $attribute)
                                <div class="card mb-8 rounded-4" wire:key="attribute-{{ $index }}" 
                                    x-data="{
                                        name: '',
                                        slug: '',
                                        generateSlug() {
                                            this.slug = this.name.toLowerCase()
                                                .replace(/ /g, '-')
                                                .replace(/[^\w-]+/g, '');
                                            $wire.set('attributeList.{{ $index }}.slug', this.slug);
                                        }
                                    }" 
                                    x-init="$watch('name', value => value && generateSlug())">
                                    
                                    <div class="card-header p-7 bg-transparent d-flex justify-content-between align-items-center">
                                        <h4 class="fs-18 mb-0 font-weight-500">Attribute Information</h4>
                                        @if($index > 0)
                                            <button type="button" wire:click="removeAttributeField({{ $index }})"
                                                class="btn btn-danger btn-sm">
                                                <i class="far fa-times"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                    <div class="card-body p-7">
                                        <div class="row">
                                            <!-- Attribute Name -->
                                            <div class="col-lg-6">
                                                <div class="mb-8">
                                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                        Attribute Name
                                                    </label>
                                                    <input type="text" 
                                                        x-model="name"
                                                        @input="generateSlug()"
                                                        wire:model="attributeList.{{ $index }}.name"
                                                        class="form-control" 
                                                        placeholder="e.g., Size, Color, Material">
                                                    @error('attributeList.'.$index.'.name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Slug -->
                                            <div class="col-lg-6">
                                                <div class="mb-8">
                                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Slug</label>
                                                    <input type="text" 
                                                        x-model="slug"
                                                        wire:model="attributeList.{{ $index }}.slug"
                                                        class="form-control" 
                                                        placeholder="auto-generated-slug">
                                                    @error('attributeList.'.$index.'.slug')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <small class="text-muted">Slug will be auto-generated from attribute name</small>
                                                </div>
                                            </div>

                                            <!-- Attribute Values (Tags) -->
                                            <div class="col-lg-12">
                                                <div class="mb-8">
                                                    <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                        Attribute Values
                                                    </label>
                                                    
                                                    <div x-data="{ inputValue: '' }">
                                                        <!-- Tags Display Container -->
                                                        <div class="tag-container" 
                                                            style="display: flex; flex-wrap: wrap; gap: 8px; padding: 12px; border: 1px solid #dee2e6; border-radius: 0.375rem; min-height: 50px; background: #fff; cursor: text;"
                                                            @click="$refs.tagInput{{ $index }}.focus()">
                                                            
                                                            @if(isset($attributeList[$index]['values']) && count($attributeList[$index]['values']) > 0)
                                                                @foreach($attributeList[$index]['values'] as $valueIndex => $value)
                                                                    <span class="tag-item" 
                                                                        style="display: inline-flex; align-items: center; padding: 6px 14px; background: #0d6efd; color: white; border-radius: 20px; font-size: 14px; gap: 8px;"
                                                                        wire:key="value-{{ $index }}-{{ $valueIndex }}">
                                                                        {{ $value }}
                                                                        <span wire:click="removeValue({{ $index }}, {{ $valueIndex }})" 
                                                                            style="cursor: pointer; font-weight: bold; opacity: 0.8; font-size: 18px; line-height: 1;"
                                                                            onmouseover="this.style.opacity='1'" 
                                                                            onmouseout="this.style.opacity='0.8'">&times;</span>
                                                                    </span>
                                                                @endforeach
                                                            @endif
                                                            
                                                            <!-- Input for new value -->
                                                            <input type="text" 
                                                                x-model="inputValue"
                                                                @keydown.enter.prevent="if(inputValue.trim()) { $wire.addValue({{ $index }}, inputValue.trim()); inputValue = ''; }"
                                                                @keydown.comma.prevent="if(inputValue.trim()) { $wire.addValue({{ $index }}, inputValue.trim()); inputValue = ''; }"
                                                                x-ref="tagInput{{ $index }}"
                                                                style="border: none; outline: none; background: transparent; flex: 1; min-width: 150px; padding: 5px; font-size: 14px;"
                                                                placeholder="Type and press Enter or comma">
                                                        </div>
                                                        
                                                        <small class="text-muted d-block mt-2">
                                                            <i class="far fa-info-circle me-1"></i>Type a value and press <strong>Enter</strong> or <strong>comma (,)</strong> to add. Example: Small, Medium, Large
                                                        </small>
                                                    </div>
                                                    
                                                    @error('attributeList.'.$index.'.values')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Add More Button -->
                            <button type="button" wire:click="addAttributeField" class="btn btn-outline-primary w-100 mb-4">
                                <i class="far fa-plus me-2"></i>Add More Attribute
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
                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-check mb-5">
                                            <input class="form-check-input" type="checkbox" wire:model="status" checked>
                                            <span class="form-check-label"> Attribute Status </span>
                                        </label>
                                    </div>

                                    <!-- Info Box -->
                                    <div class="alert alert-info mb-0" role="alert">
                                        <strong><i class="far fa-lightbulb me-1"></i> Tip:</strong>
                                        <p class="mb-0 small">You can add multiple attributes at once. Each attribute can have multiple values as tags.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Card -->
                            <div class="card rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Summary</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Total Attributes:</span>
                                        <strong>{{ count($attributeList) }}</strong>
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