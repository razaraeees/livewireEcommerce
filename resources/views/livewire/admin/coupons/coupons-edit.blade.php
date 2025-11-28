<div>
    <div class="dashboard-page-content">
        <!-- FORM START -->
        <form wire:submit.prevent="update">
            <div class="row mb-9 align-items-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 mb-8 mb-sm-0">
                            <h2 class="fs-4 mb-0">Edit Coupon</h2>
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

                            <!-- Update -->
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
                    <div class="row">
                        <!-- Left Side -->
                        <div class="col-lg-8">
                            <div class="card mb-8 rounded-4" x-data="{
                                coupon_code: @entangle('coupon_code'),
                                generateCouponCode() {
                                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                                    let code = '';
                                    for (let i = 0; i < 8; i++) {
                                        code += chars.charAt(Math.floor(Math.random() * chars.length));
                                    }
                                    this.coupon_code = code;
                                }
                            }">

                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18 mb-0 font-weight-500">Basic Information</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="row">
                                        <!-- Coupon Type (Fixed according to database) -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Code Generation <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model="code_type" class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="manual">Manual</option>
                                                    <option value="auto">Auto Generate</option>
                                                </select>
                                                @error('code_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Coupon Code -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Coupon Code <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" wire:model="coupon_code" class="form-control"
                                                        placeholder="e.g., SAVE20"
                                                        x-bind:readonly="$wire.code_type !== 'manual'">
                                                    <button type="button" x-show="$wire.code_type === 'auto'"
                                                        wire:click="generateCouponCode"
                                                        class="btn btn-outline-secondary">
                                                        <i class="fas fa-sync-alt"></i> Generate
                                                    </button>
                                                </div>
                                                @error('coupon_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Coupon Type (Database: one_time, multiple) -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Usage Type <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model="coupon_type" class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="one_time">One Time Use</option>
                                                    <option value="multiple">Multiple Use</option>
                                                </select>
                                                @error('coupon_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Discount Type -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Discount Type <span class="text-danger">*</span>
                                                </label>
                                                <select wire:model="discount_type" class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="percentage">Percentage (%)</option>
                                                    <option value="fixed">Fixed Amount</option>
                                                </select>
                                                @error('discount_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Discount Value -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Discount Value <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" wire:model="discount_value" class="form-control"
                                                    placeholder="e.g., 20" step="0.01" min="0">
                                                @error('discount_value')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Minimum Purchase Amount -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Minimum Purchase
                                                    Amount</label>
                                                <input type="number" wire:model="minimum_purchase_amount"
                                                    class="form-control" placeholder="e.g., 1000" step="0.01"
                                                    min="0">
                                                @error('minimum_purchase_amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Leave empty for no minimum</small>
                                            </div>
                                        </div>

                                        <!-- Maximum Discount Amount -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Maximum
                                                    Discount Amount</label>
                                                <input type="number" wire:model="maximum_discount_amount"
                                                    class="form-control" placeholder="e.g., 500" step="0.01"
                                                    min="0">
                                                @error('maximum_discount_amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Leave empty for no maximum</small>
                                            </div>
                                        </div>

                                        <!-- Start Date -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Start Date <span class="text-danger">*</span>
                                                </label>
                                                <input type="datetime-local" wire:model="start_date"
                                                    class="form-control">
                                                @error('start_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">End
                                                    Date</label>
                                                <input type="datetime-local" wire:model="end_date"
                                                    class="form-control">
                                                @error('end_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Leave empty for unlimited</small>
                                            </div>
                                        </div>

                                        <!-- Usage Limit -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Total Usage
                                                    Limit</label>
                                                <input type="number" wire:model="usage_limit" class="form-control"
                                                    placeholder="e.g., 100" min="0">
                                                @error('usage_limit')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Leave empty for unlimited</small>
                                            </div>
                                        </div>

                                        <!-- Usage Limit Per User -->
                                        <div class="col-lg-6">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Usage Limit Per
                                                    User</label>
                                                <input type="number" wire:model="usage_limit_per_user"
                                                    class="form-control" placeholder="e.g., 1" min="0">
                                                @error('usage_limit_per_user')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <small class="text-muted">Leave empty for unlimited</small>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label
                                                    class="mb-4 fs-13px ls-1 fw-bold text-uppercase">Description</label>
                                                <textarea wire:model="description" class="form-control" rows="4"
                                                    placeholder="Enter coupon description or terms & conditions"></textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Categories (Dropdown Select to Tags) -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Categories
                                                </label>

                                                <!-- Tags Display -->
                                                <div class="tag-container mb-3"
                                                    style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px; padding: 8px; border: 1px solid #dee2e6; border-radius: 0.375rem; background: #f8f9fa;">
                                                    @if (count($categories) > 0)
                                                        @foreach ($categories as $catIndex => $categoryId)
                                                            @php
                                                                $category = $allCategories->firstWhere(
                                                                    'id',
                                                                    $categoryId,
                                                                );
                                                            @endphp
                                                            @if ($category)
                                                                <span class="badge bg-primary"
                                                                    style="padding: 8px 14px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;"
                                                                    wire:key="cat-{{ $categoryId }}">
                                                                    {{ $category->category_name }}
                                                                    <span
                                                                        wire:click="removeCategory({{ $catIndex }})"
                                                                        style="cursor: pointer; font-weight: bold; font-size: 18px; line-height: 1;"
                                                                        onmouseover="this.style.opacity='0.7'"
                                                                        onmouseout="this.style.opacity='1'">&times;</span>
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-muted">No categories selected</small>
                                                    @endif
                                                </div>

                                                <!-- Select Dropdown -->
                                                <select wire:model.live="selectedCategory" class="form-control">
                                                    <option value="">-- Select Category --</option>
                                                    @foreach ($allCategories as $cat)
                                                        @if (!in_array($cat->id, $categories))
                                                            <option value="{{ $cat->id }}">
                                                                {{ $cat->category_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('categories')
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Brands (Dropdown Select to Tags) -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Brands
                                                </label>

                                                <!-- Tags Display -->
                                                <div class="tag-container mb-3"
                                                    style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px; padding: 8px; border: 1px solid #dee2e6; border-radius: 0.375rem; background: #f8f9fa;">
                                                    @if (count($brands) > 0)
                                                        @foreach ($brands as $brandIndex => $brandId)
                                                            @php
                                                                $brand = $allBrands->firstWhere('id', $brandId);
                                                            @endphp
                                                            @if ($brand)
                                                                <span class="badge bg-primary"
                                                                    style="padding: 8px 14px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;"
                                                                    wire:key="brand-{{ $brandId }}">
                                                                    {{ $brand->name }}
                                                                    <span
                                                                        wire:click="removeBrand({{ $brandIndex }})"
                                                                        style="cursor: pointer; font-weight: bold; font-size: 18px; line-height: 1;"
                                                                        onmouseover="this.style.opacity='0.7'"
                                                                        onmouseout="this.style.opacity='1'">&times;</span>
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-muted">No brands selected</small>
                                                    @endif
                                                </div>

                                                <!-- Select Dropdown -->
                                                <select wire:model.live="selectedBrand" class="form-control">
                                                    <option value="">-- Select Brand --</option>
                                                    @foreach ($allBrands as $brand)
                                                        @if (!in_array($brand->id, $brands))
                                                            <option value="{{ $brand->id }}">{{ $brand->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('brands')
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Products (Dropdown Select to Tags) -->
                                        <div class="col-lg-12">
                                            <div class="mb-8">
                                                <label class="mb-4 fs-13px ls-1 fw-bold text-uppercase">
                                                    Products
                                                </label>

                                                <!-- Tags Display -->
                                                <div class="tag-container mb-3"
                                                    style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 40px; padding: 8px; border: 1px solid #dee2e6; border-radius: 0.375rem; background: #f8f9fa;">
                                                    @if (count($products) > 0)
                                                        @foreach ($products as $productIndex => $productId)
                                                            @php
                                                                $product = $allProducts->firstWhere('id', $productId);
                                                            @endphp
                                                            @if ($product)
                                                                <span class="badge bg-primary"
                                                                    style="padding: 8px 14px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;"
                                                                    wire:key="product-{{ $productId }}">
                                                                    {{ $product->product_name }}
                                                                    <span
                                                                        wire:click="removeProduct({{ $productIndex }})"
                                                                        style="cursor: pointer; font-weight: bold; font-size: 18px; line-height: 1;"
                                                                        onmouseover="this.style.opacity='0.7'"
                                                                        onmouseout="this.style.opacity='1'">&times;</span>
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <small class="text-muted">No products selected</small>
                                                    @endif
                                                </div>

                                                <!-- Select Dropdown -->
                                                <select wire:model.live="selectedProduct" class="form-control">
                                                    <option value="">-- Select Product --</option>
                                                    @foreach ($allProducts as $product)
                                                        @if (!in_array($product->id, $products))
                                                            <option value="{{ $product->id }}">
                                                                {{ $product->product_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('products')
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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
                                            <input class="form-check-input" type="checkbox" wire:model="status"
                                                checked>
                                            <span class="form-check-label"> Coupon Status </span>
                                        </label>
                                    </div>

                                    <!-- Info Box -->
                                    <div class="alert alert-info mb-0" role="alert">
                                        <strong><i class="far fa-lightbulb me-1"></i> Tip:</strong>
                                        <p class="mb-0 small">Set discount rules and apply to specific categories,
                                            brands, or products.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Card -->
                            <div class="card rounded-4">
                                <div class="card-header p-7 bg-transparent">
                                    <h4 class="fs-18px mb-0 font-weight-500">Coupon Summary</h4>
                                </div>
                                <div class="card-body p-7">
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Coupon Code</small>
                                        <strong>{{ $coupon_code ?? 'Not set' }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Discount</small>
                                        <strong>
                                            @if ($discount_type == 'percentage')
                                                {{ $discount_value ?? 0 }}%
                                            @elseif($discount_type == 'fixed')
                                                Rs. {{ $discount_value ?? 0 }}
                                            @else
                                                Not set
                                            @endif
                                        </strong>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Status</small>
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
