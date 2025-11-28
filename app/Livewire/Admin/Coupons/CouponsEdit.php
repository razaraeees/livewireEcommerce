<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;

class CouponsEdit extends Component
{
    public $couponId;
    public $coupon_code, $code_type, $coupon_type, $discount_type;
    public $discount_value, $minimum_purchase_amount, $maximum_discount_amount;
    public $start_date, $end_date, $usage_limit, $usage_limit_per_user;
    public $status, $description;

    public $categories = [];
    public $brands = [];
    public $products = [];

    public $selectedCategory = '';
    public $selectedBrand = '';
    public $selectedProduct = '';

    public $allCategories;
    public $allBrands;
    public $allProducts;

    protected function rules()
    {
        return [
            'code_type' => 'required|in:manual,auto',
            'coupon_type' => 'required|in:one_time,multiple',
            'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code,' . $this->couponId,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_purchase_amount' => 'nullable|numeric|min:0',
            'maximum_discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'categories' => 'nullable|array',
            'brands' => 'nullable|array',
            'products' => 'nullable|array',
        ];
    }

    protected $messages = [
        'code_type.required' => 'Please select code generation type.',
        'coupon_type.required' => 'Please select usage type.',
        'coupon_code.required' => 'Coupon code is required.',
        'coupon_code.unique' => 'This coupon code already exists.',
        'discount_type.required' => 'Please select discount type.',
        'discount_value.required' => 'Discount value is required.',
        'start_date.required' => 'Start date is required.',
        'end_date.after' => 'End date must be after start date.',
    ];

    public function mount($id)
    {
        $this->couponId = $id;
        
        // Load all dropdown data
        $this->allCategories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->get();
        
        $this->allBrands = Brand::where('status', 1)->get();
        $this->allProducts = Product::where('status', 1)->get();

        // Load coupon data
        $this->loadCoupon();
    }

    public function loadCoupon()
    {
        $coupon = Coupon::with(['categories', 'brands', 'products'])->findOrFail($this->couponId);

        $this->coupon_code = $coupon->coupon_code;
        $this->code_type = $coupon->code_type;
        $this->coupon_type = $coupon->coupon_type;
        $this->discount_type = $coupon->discount_type;
        $this->discount_value = $coupon->discount_value;
        $this->minimum_purchase_amount = $coupon->minimum_purchase_amount;
        $this->maximum_discount_amount = $coupon->maximum_discount_amount;
        $this->start_date = $coupon->start_date ? date('Y-m-d\TH:i', strtotime($coupon->start_date)) : null;
        $this->end_date = $coupon->end_date ? date('Y-m-d\TH:i', strtotime($coupon->end_date)) : null;
        $this->usage_limit = $coupon->usage_limit;
        $this->usage_limit_per_user = $coupon->usage_limit_per_user;
        $this->description = $coupon->description;
        $this->status = $coupon->status;

        // Load relationships
        $this->categories = $coupon->categories->pluck('id')->toArray();
        $this->brands = $coupon->brands->pluck('id')->toArray();
        $this->products = $coupon->products->pluck('id')->toArray();
    }

    // Generate Random Coupon Code
    public function generateCouponCode()
    {
        $this->coupon_code = strtoupper(Str::random(8));
    }

    // When category is selected
    public function updatedSelectedCategory($value)
    {
        if (!empty($value) && !in_array($value, $this->categories)) {
            $this->categories[] = (int) $value;
        }
        $this->selectedCategory = '';
    }

    // When brand is selected
    public function updatedSelectedBrand($value)
    {
        if (!empty($value) && !in_array($value, $this->brands)) {
            $this->brands[] = (int) $value;
        }
        $this->selectedBrand = '';
    }

    // When product is selected
    public function updatedSelectedProduct($value)
    {
        if (!empty($value) && !in_array($value, $this->products)) {
            $this->products[] = (int) $value;
        }
        $this->selectedProduct = '';
    }

    // Remove Category
    public function removeCategory($index)
    {
        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
    }

    // Remove Brand
    public function removeBrand($index)
    {
        unset($this->brands[$index]);
        $this->brands = array_values($this->brands);
    }

    // Remove Product
    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    // Save as Draft
    public function saveDraft()
    {
        $this->status = false;
        $this->update(true);
    }

    // Main Update Method
    public function update($isDraft = false)
    {
        if ($isDraft) {
            $this->validate([
                'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code,' . $this->couponId,
                'code_type' => 'required|in:manual,auto',
            ]);
        } else {
            $this->validate();
        }

        try {
            $coupon = Coupon::findOrFail($this->couponId);

            $coupon->update([
                'coupon_code' => strtoupper($this->coupon_code),
                'code_type' => $this->code_type,
                'coupon_type' => $this->coupon_type,
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_value,
                'minimum_purchase_amount' => $this->minimum_purchase_amount ?: null,
                'maximum_discount_amount' => $this->maximum_discount_amount ?: null,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date ?: null,
                'usage_limit' => $this->usage_limit ?: null,
                'usage_limit_per_user' => $this->usage_limit_per_user ?: null,
                'description' => $this->description ?: null,
                'status' => $this->status ? 1 : 0,
            ]);

            // Sync relationships
            $coupon->categories()->sync($this->categories ?? []);
            $coupon->brands()->sync($this->brands ?? []);
            $coupon->products()->sync($this->products ?? []);

            $message = $isDraft ? 'Coupon Edit as draft!' : 'Coupon Edit successfully!';
            $this->dispatch('show-toast', type: 'success', message: $message);

            
            return redirect()->route('admin.coupon');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.coupons.coupons-edit');
    }
}