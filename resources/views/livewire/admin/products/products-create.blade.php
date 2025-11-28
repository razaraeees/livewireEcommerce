<div x-data="{
    name: '',
    slug: '',
    generateSlug() {
        this.slug = this.name.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
        $wire.set('product_slug', this.slug);
    }
}" x-init="$watch('name', value => value && generateSlug())">
    <div class="dashboard-page-content">

        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New Product</h2>
                        </div>

                        <div class="col-sm-6 d-flex flex-wrap justify-content-sm-end">
                            <button type="button" wire:click="saveDraft" class="btn btn-outline-primary me-4"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveDraft">Save to draft</span>
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
                            <!-- Product Information -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">Product Information</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">

                                        <!-- Product Name -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Product Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" x-model="name" @input="generateSlug()"
                                                    wire:model="product_name" class="form-control"
                                                    placeholder="Enter product name" required>
                                                @error('product_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Product Slug -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Product Slug <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" x-model="slug" wire:model="product_slug"
                                                    class="form-control" placeholder="Auto-generated slug" required>
                                                @error('product_slug')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Category <span class="text-danger">*</span>
                                                </label>

                                                <select wire:model="category_id" class="form-control" required>
                                                    <option value="">Select Category</option>

                                                    @php
                                                        function renderCategory($category, $prefix = '') {
                                                            echo '<option value="'.$category->id.'">'.$prefix.$category->category_name.'</option>';

                                                            if (!empty($category->children)) {
                                                                foreach ($category->children as $child) {
                                                                    renderCategory($child, $prefix.'— ');
                                                                }
                                                            }
                                                        }

                                                        foreach ($this->categoryTree as $cat) {
                                                            renderCategory($cat);
                                                        }
                                                    @endphp
                                                </select>

                                                @error('category_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>




                                        <!-- Brand -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Brand <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model="brand_id" class="form-control" required>
                                                    <option value="">Select Brand</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('brand_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Product Code -->
                                        <div class="col-lg-4">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Product Code <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" wire:model="product_code" class="form-control"
                                                    placeholder="e.g., PRD-001">
                                                @error('product_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>

                                        <!-- Product Weight -->
                                        <div class="col-lg-4">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Weight (kg)
                                                </label>
                                                <input type="number" wire:model="product_weight" class="form-control"
                                                    placeholder="0.00" step="0.01">
                                            </div>
                                        </div>

                                        <!-- Product Price -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Price <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" wire:model="product_price" class="form-control"
                                                    placeholder="0.00" step="0.01" required>
                                                @error('product_price')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Product Discount -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Discount (%)
                                                </label>
                                                <input type="number" wire:model="product_discount"
                                                    class="form-control" placeholder="0" min="0"
                                                    max="100" step="0.01">
                                            </div>
                                        </div>

                                        <!-- Stock -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Stock Quantity <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" wire:model="stock" class="form-control"
                                                    placeholder="0" min="0">
                                                @error('stock')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>

                                        <!-- Stock Status -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Stock Status
                                                </label>
                                                <select wire:model="stock_status" class="form-control">
                                                    <option value="in_stock">In Stock</option>
                                                    <option value="out_of_stock">Out of Stock</option>
                                                    {{-- <option value="pre_order">Pre Order</option> --}}
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Short Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Short
                                                    Description</label>
                                                <textarea wire:model="short_description" class="form-control" rows="3"
                                                    placeholder="Brief product description..."></textarea>
                                            </div>
                                        </div>

                                        <!-- Long Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Long
                                                    Description</label>
                                                <textarea wire:model="long_description" class="form-control" rows="6"
                                                    placeholder="Detailed product description..."></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- NEW: Variant Selection System -->
                            <!-- Variant Selection System -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">
                                        <i class="far fa-layer-group me-2"></i> Variant Selection
                                    </h4>
                                    <p class="text-muted small mb-0 mt-2">Select variant types and their values to
                                        auto-generate all combinations</p>
                                </div>
                                <div class="card-body p-7">

                                    @foreach ($selectedVariants as $key => $selectedVariant)
                                        <div class="card mb-4 border"
                                            wire:key="variant-selection-{{ $key }}">
                                            <div class="card-body p-4">
                                                <div class="row align-items-start">
                                                    <div class="col-md-5">
                                                        <label class="form-label fw-bold">Select Variant Type</label>
                                                        <select wire:model.live="selectedVariants.{{ $key }}"
                                                            class="form-select">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($availableVariants as $variant)
                                                                <option value="{{ $variant->id }}">
                                                                    {{ $variant->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        @if (!empty($selectedVariants[$key]))
                                                            @php
                                                                $currentVariant = $availableVariants->find(
                                                                    $selectedVariants[$key],
                                                                );
                                                            @endphp
                                                            @if ($currentVariant)
                                                                <label class="form-label fw-bold">
                                                                    Select Values for
                                                                    <strong>{{ $currentVariant->name }}</strong>
                                                                </label>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($currentVariant->variantValues as $value)
                                                                        @php
                                                                            $isChecked =
                                                                                isset(
                                                                                    $selectedVariantValues[
                                                                                        $currentVariant->id
                                                                                    ],
                                                                                ) &&
                                                                                is_array(
                                                                                    $selectedVariantValues[
                                                                                        $currentVariant->id
                                                                                    ],
                                                                                ) &&
                                                                                in_array(
                                                                                    $value->id,
                                                                                    $selectedVariantValues[
                                                                                        $currentVariant->id
                                                                                    ],
                                                                                );
                                                                        @endphp
                                                                        <label
                                                                            class="btn btn-sm {{ $isChecked ? 'btn-primary' : 'btn-outline-primary' }}"
                                                                            style="cursor: pointer;">
                                                                            <input type="checkbox"
                                                                                wire:model.live="selectedVariantValues.{{ $currentVariant->id }}"
                                                                                value="{{ $value->id }}"
                                                                                class="form-check-input me-1 d-none">
                                                                            {{ $value->value }}
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @else
                                                            <label class="form-label text-muted">Select a variant type
                                                                first</label>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-1 text-end">
                                                        @if ($key > 0)
                                                            <button type="button"
                                                                wire:click.prevent="removeVariantSelection('{{ $key }}')"
                                                                class="btn btn-sm btn-danger mt-4"
                                                                title="Remove this variant">
                                                                <i class="far fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <button type="button" wire:click="addVariantSelection"
                                        class="btn btn-outline-primary mb-4">
                                        <i class="far fa-plus me-1"></i> Add Another Variant Type
                                    </button>

                                    {{-- Manual Generate Button (Optional - auto-generates on change) --}}
                                    @if (count($selectedVariantValues) > 0)
                                        <div class="alert alert-info">
                                            <strong><i class="far fa-info-circle me-2"></i>Info:</strong>
                                            Combinations auto-generate when you select values.
                                            You can also click the button below to manually refresh.
                                        </div>

                                        <button type="button" wire:click="generateCombinations"
                                            class="btn btn-success" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="generateCombinations">
                                                <i class="far fa-magic me-2"></i> Refresh Combinations
                                            </span>
                                            <span wire:loading wire:target="generateCombinations">
                                                <span class="spinner-border spinner-border-sm me-2"></span>
                                                Generating...
                                            </span>
                                        </button>
                                    @else
                                        <div class="alert alert-secondary">
                                            <i class="far fa-info-circle me-2"></i>
                                            Select variant types and their values above to generate combinations
                                            automatically.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Generated Variant Combinations -->
                            @if (count($generatedCombinations) > 0)
                                <div class="card mb-8 rounded-4">
                                    <div class="card-header p-7 bg-transparent">
                                        <h4 class="fs-18 mb-0 font-weight-500">
                                            <i class="far fa-boxes me-2"></i> Generated Variant Combinations
                                            <span class="badge bg-primary">{{ count($generatedCombinations) }}</span>
                                        </h4>
                                        <p class="text-muted small mb-0 mt-2">Fill details for each auto-generated
                                            variant</p>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($generatedCombinations as $index => $combination)
                                            <div class="card mb-4" wire:key="combination-{{ $combination['key'] }}">
                                                <div class="card-header bg-light py-3">
                                                    <strong class="text-primary">
                                                        <i class="far fa-tag me-2"></i>{{ $combination['label'] }}
                                                    </strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <!-- SKU -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">SKU <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text"
                                                                    wire:model="generatedCombinations.{{ $index }}.sku"
                                                                    class="form-control @error('generatedCombinations.' . $index . '.sku') is-invalid @enderror"
                                                                    placeholder="e.g., PROD-001">
                                                                @error('generatedCombinations.' . $index . '.sku')
                                                                    <span
                                                                        class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Barcode -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">Barcode</label>
                                                                <input type="text"
                                                                    wire:model="generatedCombinations.{{ $index }}.barcode"
                                                                    class="form-control @error('generatedCombinations.' . $index . '.barcode') is-invalid @enderror"
                                                                    placeholder="123456789">
                                                                @error('generatedCombinations.' . $index . '.barcode')
                                                                    <span
                                                                        class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Price -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">Price <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number"
                                                                    wire:model="generatedCombinations.{{ $index }}.price"
                                                                    class="form-control @error('generatedCombinations.' . $index . '.price') is-invalid @enderror"
                                                                    placeholder="0.00" step="0.01">
                                                                @error('generatedCombinations.' . $index . '.price')
                                                                    <span
                                                                        class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Sale Price -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">Sale Price</label>
                                                                <input type="number"
                                                                    wire:model="generatedCombinations.{{ $index }}.sale_price"
                                                                    class="form-control @error('generatedCombinations.' . $index . '.sale_price') is-invalid @enderror"
                                                                    placeholder="0.00" step="0.01">
                                                                @error('generatedCombinations.' . $index .
                                                                    '.sale_price')
                                                                    <span
                                                                        class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Stock -->
                                                        <div class="col-lg-4">
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">Stock <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number"
                                                                    wire:model="generatedCombinations.{{ $index }}.stock"
                                                                    class="form-control @error('generatedCombinations.' . $index . '.stock') is-invalid @enderror"
                                                                    placeholder="0" min="0">
                                                                @error('generatedCombinations.' . $index . '.stock')
                                                                    <span
                                                                        class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Weight -->
                                                        <div class="col-lg-4">
                                                            <div class="mb-4">
                                                                <label class="form-label fw-bold">Weight (kg)</label>
                                                                <input type="number"
                                                                    wire:model="generatedCombinations.{{ $index }}.weight"
                                                                    class="form-control @error('generatedCombinations.' . $index . '.weight') is-invalid @enderror"
                                                                    placeholder="0.00" step="0.01">
                                                                @error('generatedCombinations.' . $index . '.weight')
                                                                    <span
                                                                        class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Status -->
                                                        <div class="col-lg-4">
                                                            <div class="mb-4">
                                                                <label class="form-check mt-4">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        wire:model="generatedCombinations.{{ $index }}.status"
                                                                        checked>
                                                                    <span
                                                                        class="form-check-label fw-bold">Active</span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Variant Images -->
                                                        <div class="col-lg-12 mt-3">
                                                            <label class="form-label fw-bold">
                                                                <i class="far fa-images me-1"></i> Variant Images
                                                            </label>
                                                            <input type="file"
                                                                wire:model="generatedCombinations.{{ $index }}.images"
                                                                class="form-control @error('generatedCombinations.' . $index . '.images.*') is-invalid @enderror"
                                                                accept="image/*" multiple>
                                                            @error('generatedCombinations.' . $index . '.images.*')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror

                                                            @if (isset($combination['images']) && count($combination['images']) > 0)
                                                                <div class="row mt-3">
                                                                    @foreach ($combination['images'] as $imgKey => $image)
                                                                        <div class="col-3 mb-2">
                                                                            <div class="position-relative">
                                                                                <img src="{{ $image->temporaryUrl() }}"
                                                                                    class="w-100 rounded"
                                                                                    style="height: 80px; object-fit: cover;">
                                                                                <button type="button"
                                                                                    wire:click="removeCombinationImage({{ $index }}, {{ $imgKey }})"
                                                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                                                    style="padding: 2px 6px; font-size: 10px;">&times;</button>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <!-- SEO Information -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">
                                        <i class="far fa-search me-2"></i> SEO Information
                                    </h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta
                                                    Title</label>
                                                <input type="text" wire:model="meta_title" class="form-control"
                                                    placeholder="SEO Title">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta
                                                    Description</label>
                                                <textarea wire:model="meta_description" class="form-control" rows="3" placeholder="SEO Description"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-0">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Meta
                                                    Keywords</label>
                                                <input type="text" wire:model="meta_keywords" class="form-control"
                                                    placeholder="keyword1, keyword2">
                                                <small class="text-muted">Separate keywords with commas</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="col-lg-4">
                            <!-- Product Settings -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Product Settings</h4>
                                </div>
                                <div class="card-body p-7">

                                    <div class="mb-0">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Display Order</label>
                                        <input type="number" wire:model="order_by" class="form-control"
                                            placeholder="0" min="0">
                                        <small class="text-muted">Lower number = higher priority</small>
                                    </div>


                                    <div class="mb-8">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="is_featured">
                                            <span class="form-check-label">
                                                <i class="far fa-star text-warning"></i> Featured Product
                                            </span>
                                        </label>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="status"
                                                checked>
                                            <span class="form-check-label">
                                                <i class="far fa-check-circle text-success"></i> Product Active
                                            </span>
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <!-- Thumbnail Image -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">
                                        <i class="far fa-image me-2"></i> Thumbnail Image
                                    </h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center position-relative">
                                        @if ($thumbnail_image)
                                            <div class="position-relative">
                                                <img src="{{ $thumbnail_image->temporaryUrl() }}"
                                                    class="w-100 rounded mb-4"
                                                    style="max-height: 250px; object-fit: cover;">
                                                <button type="button" wire:click="$set('thumbnail_image', null)"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}"
                                                width="102" class="d-block mx-auto mb-4">
                                            <p class="text-muted">No image uploaded</p>
                                        @endif

                                        <input type="file" wire:model="thumbnail_image" class="form-control"
                                            accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB</small>

                                        <div wire:loading wire:target="thumbnail_image" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary"></div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Gallery -->
                            <div class="card rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">
                                        <i class="far fa-images me-2"></i> Product Gallery
                                    </h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="input-upload text-center">
                                        <input type="file" wire:model="product_images" class="form-control"
                                            accept="image/*" multiple>
                                        <small class="text-muted d-block mt-2">Upload multiple images</small>

                                        <div wire:loading wire:target="product_images" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary"></div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>
                                    </div>

                                    @if ($product_images)
                                        <div class="row mt-4">
                                            @foreach ($product_images as $key => $image)
                                                <div class="col-6 mb-3">
                                                    <div class="position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}" class="w-100 rounded"
                                                            style="height: 100px; object-fit: cover;">
                                                        <button type="button"
                                                            wire:click="removeImage({{ $key }})"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                            style="padding: 2px 6px;">&times;</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Attributes (Add after Product Gallery in right column) -->
                            @if (isset($availableAttributes) && $availableAttributes->count() > 0)
                                <div class="card rounded-4" style="margin-top: 30px">
                                    <div class="card-header p-7 bg-transparent">
                                        <h4 class="fs-18px mb-0 font-weight-500">
                                            <i class="far fa-list-alt me-2"></i> Product Attributes
                                        </h4>
                                        <p class="text-muted small mb-0 mt-2">Add product specifications</p>
                                    </div>
                                    <div class="card-body p-7">
                                        @foreach ($productAttributes as $index => $attribute)
                                            <div class="card mb-3 border" wire:key="attr-{{ $index }}">
                                                <div class="card-body p-5">
                                                    <!-- Attribute Dropdown -->
                                                    <div class="mb-5">
                                                        <label
                                                            class="form-label fs-13px ls-1 fw-bold text-uppercase">Attribute</label>
                                                        <select
                                                            wire:model.live="productAttributes.{{ $index }}.attribute_id"
                                                            class="form-select">
                                                            <option value="">-- Select Attribute --</option>
                                                            @foreach ($availableAttributes as $attr)
                                                                {{-- Hide already selected attributes except current --}}
                                                                @php
                                                                    $isUsed =
                                                                        collect($productAttributes)
                                                                            ->where('attribute_id', $attr->id)
                                                                            ->count() > 0 &&
                                                                        ($attribute['attribute_id'] ?? '') != $attr->id;
                                                                @endphp
                                                                @if (!$isUsed)
                                                                    <option value="{{ $attr->id }}">
                                                                        {{ $attr->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Value Dropdown (shows when attribute selected) -->
                                                    @if (!empty($attribute['attribute_id']))
                                                        @php
                                                            $selectedAttribute = $availableAttributes->find(
                                                                $attribute['attribute_id'],
                                                            );
                                                        @endphp
                                                        @if ($selectedAttribute && $selectedAttribute->attributeValue && $selectedAttribute->attributeValue->count() > 0)
                                                            <div class="mb-3">
                                                                <label
                                                                    class="form-label fs-13px ls-1 fw-bold text-uppercase">Value</label>
                                                                <select
                                                                    wire:model="productAttributes.{{ $index }}.value_id"
                                                                    class="form-select">
                                                                    <option value="">-- Select Value --</option>
                                                                    @foreach ($selectedAttribute->attributeValue as $value)
                                                                        <option value="{{ $value->id }}">
                                                                            {{ $value->value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-warning mb-0">
                                                                <small>No values available for this attribute. Please
                                                                    add values first.</small>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    <!-- Remove Button -->
                                                    @if ($index > 0)
                                                        <button type="button"
                                                            wire:click="removeAttributeRow({{ $index }})"
                                                            class="btn btn-sm btn-outline-danger w-100 mt-2">
                                                            <i class="far fa-trash-alt me-1"></i> Remove
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Add More Button -->
                                        <button type="button" wire:click="addAttributeRow"
                                            class="btn btn-sm btn-outline-primary w-100">
                                            <i class="far fa-plus me-1"></i> Add More Attribute
                                        </button>

                                        <!-- Preview Selected Attributes -->
                                        @php
                                            $validAttributes = collect($productAttributes)->filter(function ($a) {
                                                return !empty($a['attribute_id']) && !empty($a['value_id']);
                                            });
                                        @endphp

                                        @if ($validAttributes->count() > 0)
                                            <div class="alert alert-info mt-3 mb-0">
                                                <strong class="small">Selected Attributes:</strong>
                                                <div class="mt-2">
                                                    @foreach ($validAttributes as $attr)
                                                        @php
                                                            $selectedAttr = $availableAttributes->find(
                                                                $attr['attribute_id'],
                                                            );
                                                            $selectedValue =
                                                                $selectedAttr && $selectedAttr->attributeValues
                                                                    ? $selectedAttr->attributeValues->find(
                                                                        $attr['value_id'],
                                                                    )
                                                                    : null;
                                                        @endphp
                                                        @if ($selectedAttr && $selectedValue)
                                                            <span class="badge bg-primary me-1 mb-1">
                                                                {{ $selectedAttr->name }}: {{ $selectedValue->value }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
