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

        <!-- FORM START -->
        <form wire:submit.prevent="store">

            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Add New Product</h2>
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
                                                    wire:model="product_name" class="form-control" placeholder="Enter product name" required>
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
                                                <small class="text-muted">Slug will be auto-generated from product name</small>
                                            </div>
                                        </div>

                                        <!-- Category -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Category <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model="category_id" class="form-control" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
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
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
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
                                                    Product Code
                                                </label>
                                                <input type="text" wire:model="product_code" class="form-control" placeholder="e.g., PRD-001">
                                                @error('product_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Product Color -->
                                        <div class="col-lg-4">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Product Color
                                                </label>
                                                <input type="text" wire:model="product_color" class="form-control" placeholder="e.g., Red, Blue">
                                                @error('product_color')
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
                                                <input type="number" wire:model="product_weight" class="form-control" placeholder="0.00" step="0.01">
                                                @error('product_weight')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Product Price -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Price <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" wire:model="product_price" class="form-control" placeholder="0.00" step="0.01" required>
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
                                                <input type="number" wire:model="product_discount" class="form-control" placeholder="0" min="0" max="100" step="0.01">
                                                @error('product_discount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Stock -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Stock Quantity
                                                </label>
                                                <input type="number" wire:model="stock" class="form-control" placeholder="0" min="0">
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
                                                    <option value="pre_order">Pre Order</option>
                                                </select>
                                                @error('stock_status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Short Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Short Description</label>
                                                <textarea wire:model="short_description" class="form-control" rows="3" placeholder="Brief product description..."></textarea>
                                                @error('short_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Long Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Long Description</label>
                                                <textarea wire:model="long_description" class="form-control" rows="6" placeholder="Detailed product description..."></textarea>
                                                @error('long_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Product Variants -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent d-flex justify-content-between align-items-center">
                                    <h4 class="fs-18 mb-0 font-weight-500">
                                        <i class="far fa-layer-group me-2"></i> Product Variants
                                    </h4>
                                    <button type="button" wire:click="addVariant" class="btn btn-sm btn-outline-primary">
                                        <i class="far fa-plus me-1"></i> Add Variant
                                    </button>
                                </div>
                                <div class="card-body p-7">
                                    @if(count($variants) > 0)
                                        @foreach($variants as $index => $variant)
                                            <div class="card mb-4 border" wire:key="variant-{{ $index }}">
                                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                                    <strong>Variant #{{ $index + 1 }}</strong>
                                                    @if($index > 0)
                                                        <button type="button" wire:click="removeVariant({{ $index }})" class="btn btn-sm btn-danger">
                                                            <i class="far fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <!-- SKU -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">
                                                                    SKU <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" wire:model="variants.{{ $index }}.sku" class="form-control form-control-sm" placeholder="e.g., PROD-001-L-RED">
                                                                @error('variants.'.$index.'.sku')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Barcode -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Barcode</label>
                                                                <input type="text" wire:model="variants.{{ $index }}.barcode" class="form-control form-control-sm" placeholder="e.g., 123456789012">
                                                                @error('variants.'.$index.'.barcode')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Price -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">
                                                                    Price <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="number" wire:model="variants.{{ $index }}.price" class="form-control form-control-sm" placeholder="0.00" step="0.01">
                                                                @error('variants.'.$index.'.price')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Sale Price -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Sale Price</label>
                                                                <input type="number" wire:model="variants.{{ $index }}.sale_price" class="form-control form-control-sm" placeholder="0.00" step="0.01">
                                                                @error('variants.'.$index.'.sale_price')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Stock -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Stock</label>
                                                                <input type="number" wire:model="variants.{{ $index }}.stock" class="form-control form-control-sm" placeholder="0" min="0">
                                                                @error('variants.'.$index.'.stock')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Variant Slug -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Variant Slug</label>
                                                                <input type="text" wire:model="variants.{{ $index }}.variant_slug" class="form-control form-control-sm" placeholder="auto-generated">
                                                                @error('variants.'.$index.'.variant_slug')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Combination Label -->
                                                        <div class="col-lg-12">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">
                                                                    Combination Label <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" wire:model="variants.{{ $index }}.combination_label" class="form-control form-control-sm" placeholder="e.g., Large - Red">
                                                                @error('variants.'.$index.'.combination_label')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                                <small class="text-muted">This will be displayed to customers</small>
                                                            </div>
                                                        </div>

                                                        <!-- Weight -->
                                                        <div class="col-lg-3">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Weight (kg)</label>
                                                                <input type="number" wire:model="variants.{{ $index }}.weight" class="form-control form-control-sm" placeholder="0.00" step="0.01">
                                                                @error('variants.'.$index.'.weight')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Length -->
                                                        <div class="col-lg-3">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Length (cm)</label>
                                                                <input type="number" wire:model="variants.{{ $index }}.length" class="form-control form-control-sm" placeholder="0" step="0.01">
                                                                @error('variants.'.$index.'.length')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Width -->
                                                        <div class="col-lg-3">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Width (cm)</label>
                                                                <input type="number" wire:model="variants.{{ $index }}.width" class="form-control form-control-sm" placeholder="0" step="0.01">
                                                                @error('variants.'.$index.'.width')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Height -->
                                                        <div class="col-lg-3">
                                                            <div class="mb-6">
                                                                <label class="mb-2 fs-13px ls-1 fw-bold text-uppercase">Height (cm)</label>
                                                                <input type="number" wire:model="variants.{{ $index }}.height" class="form-control form-control-sm" placeholder="0" step="0.01">
                                                                @error('variants.'.$index.'.height')
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <!-- Variant Status -->
                                                        <div class="col-lg-12">
                                                            <label class="form-check">
                                                                <input class="form-check-input" type="checkbox" wire:model="variants.{{ $index }}.status" checked>
                                                                <span class="form-check-label">
                                                                    <i class="far fa-check-circle text-success"></i> Variant Active
                                                                </span>
                                                            </label>
                                                        </div>

                                                        <!-- Variant Values Selection -->
                                                        <div class="col-lg-12 mt-4">
                                                            <label class="mb-3 fs-13px ls-1 fw-bold text-uppercase">
                                                                Select Variant Attributes
                                                            </label>
                                                            <div class="row">
                                                                @foreach($availableVariants as $availableVariant)
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="form-label fw-bold">{{ $availableVariant->name }}</label>
                                                                        <select wire:model="variants.{{ $index }}.variant_values.{{ $availableVariant->id }}" class="form-select form-select-sm">
                                                                            <option value="">Select {{ $availableVariant->name }}</option>
                                                                            @foreach($availableVariant->values as $value)
                                                                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <!-- Variant Images -->
                                                        <div class="col-lg-12 mt-4">
                                                            <label class="mb-3 fs-13px ls-1 fw-bold text-uppercase">
                                                                <i class="far fa-images me-1"></i> Variant Images
                                                            </label>
                                                            <input type="file" wire:model="variants.{{ $index }}.images" class="form-control form-control-sm" accept="image/*" multiple>
                                                            <small class="text-muted">Upload images specific to this variant</small>
                                                            
                                                            @if(isset($variants[$index]['images']) && count($variants[$index]['images']) > 0)
                                                                <div class="row mt-3">
                                                                    @foreach($variants[$index]['images'] as $imgKey => $image)
                                                                        <div class="col-3 mb-2">
                                                                            <div class="position-relative">
                                                                                <img src="{{ $image->temporaryUrl() }}" class="w-100 rounded" style="height: 80px; object-fit: cover;">
                                                                                <button type="button" wire:click="removeVariantImage({{ $index }}, {{ $imgKey }})"
                                                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="padding: 2px 6px; font-size: 10px;">&times;</button>
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
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="far fa-info-circle me-2"></i>
                                            No variants added yet. Click "Add Variant" to create product variations.
                                        </div>
                                    @endif
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
                            <!-- Product Settings -->
                            <div class="card mb-8 rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Product Settings</h4>
                                </div>
                                <div class="card-body p-7">
                                    
                                    <!-- Theme Selection -->
                                    <div class="mb-8">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                            Product Theme
                                        </label>
                                        <select wire:model="theme" class="form-control">
                                            <option value="">Select Theme</option>
                                            <option value="default">Default</option>
                                            <option value="modern">Modern</option>
                                            <option value="classic">Classic</option>
                                            <option value="minimal">Minimal</option>
                                        </select>
                                        @error('theme')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Choose a display theme for this product</small>
                                    </div>

                                    <!-- Featured Product -->
                                    <div class="mb-8">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="is_featured">
                                            <span class="form-check-label">
                                                <i class="far fa-star text-warning"></i> Featured Product
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-4">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="status" checked>
                                            <span class="form-check-label">
                                                <i class="far fa-check-circle text-success"></i> Product Status (Active)
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Display Order -->
                                    <div class="mb-0">
                                        <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Display Order</label>
                                        <input type="number" wire:model="order_by" class="form-control" placeholder="0" min="0">
                                        @error('order_by')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Lower number = higher priority</small>
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
                                                <img src="{{ $thumbnail_image->temporaryUrl() }}" class="w-100 rounded mb-4" style="max-height: 250px; object-fit: cover;">
                                                <button type="button" wire:click="$set('thumbnail_image', null)"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">&times;</button>
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/images/dashboard/upload.svg') }}" width="102"
                                                class="d-block mx-auto mb-4">
                                            <p class="text-muted">No image uploaded</p>
                                        @endif

                                        <input type="file" wire:model="thumbnail_image"
                                            class="form-control @error('thumbnail_image') is-invalid @enderror" accept="image/*">
                                        <small class="text-muted d-block mt-2">Max size: 2MB | JPG, PNG, WEBP</small>
                                        @error('thumbnail_image')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="thumbnail_image" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
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
                                        <input type="file" wire:model="product_images" 
                                            class="form-control @error('product_images.*') is-invalid @enderror" 
                                            accept="image/*" multiple>
                                        <small class="text-muted d-block mt-2">Upload multiple images | Max 2MB each</small>
                                        @error('product_images.*')
                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                        @enderror

                                        <div wire:loading wire:target="product_images" class="mt-3">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Processing...</span>
                                            </div>
                                            <span class="ms-2 text-muted">Processing...</span>
                                        </div>
                                    </div>

                                    @if ($product_images)
                                        <div class="row mt-4">
                                            @foreach($product_images as $key => $image)
                                                <div class="col-6 mb-3">
                                                    <div class="position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}" class="w-100 rounded" style="height: 100px; object-fit: cover;">
                                                        <button type="button" wire:click="removeImage({{ $key }})"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="padding: 2px 6px; font-size: 12px;">&times;</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
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