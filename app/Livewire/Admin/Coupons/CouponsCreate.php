<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;

class CouponsCreate extends Component
{
    public $coupon_code;

    public $code_type = 'manual';

    public $coupon_type;

    public $discount_type;

    public $discount_value;

    public $minimum_purchase_amount;

    public $maximum_discount_amount;

    public $start_date;

    public $end_date;

    public $usage_limit;

    public $usage_limit_per_user;

    public $status = true;

    public $description;

    public $categories = [];

    public $brands = [];

    public $products = [];

    public $selectedCategory = '';

    public $selectedBrand = '';

    public $selectedProduct = '';

    public $allCategories;

    public $allBrands;

    public $allProducts;

    protected $rules = [
        'code_type' => 'required|in:manual,auto',
        'coupon_type' => 'required|in:one_time,multiple',
        'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code',
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

    public function mount()
    {
        $this->allCategories = Category::where('status', 1)
            ->whereNull('parent_id')
            ->get();

        $this->allBrands = Brand::where('status', 1)->get();
        $this->allProducts = Product::where('status', 1)->get();
    }

    // Generate Random Coupon Code
    public function generateCouponCode()
    {
        $this->coupon_code = strtoupper(Str::random(8));
    }

    // When category is selected
    public function updatedSelectedCategory($value)
    {
        if (! empty($value) && ! in_array($value, $this->categories)) {
            $this->categories[] = (int) $value;
        }
        $this->selectedCategory = '';
    }

    // When brand is selected
    public function updatedSelectedBrand($value)
    {
        if (! empty($value) && ! in_array($value, $this->brands)) {
            $this->brands[] = (int) $value;
        }
        $this->selectedBrand = '';
    }

    // When product is selected
    public function updatedSelectedProduct($value)
    {
        if (! empty($value) && ! in_array($value, $this->products)) {
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
        $this->store(true);
    }

    // Main Store Method
    public function store($isDraft = false)
    {
        if ($isDraft) {
            $this->validate([
                'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code',
                'code_type' => 'required|in:manual,auto',
            ]);
        } else {
            $this->validate();
        }

        try {
            $coupon = Coupon::create([
                'coupon_code' => strtoupper($this->coupon_code),
                'code_type' => $this->code_type,
                'coupon_type' => $this->coupon_type,
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_value,
                'minimum_purchase_amount' => $this->minimum_purchase_amount ?: null, // 👈 Fix
                'maximum_discount_amount' => $this->maximum_discount_amount ?: null, // 👈 Fix
                'start_date' => $this->start_date,
                'end_date' => $this->end_date ?: null, // 👈 Fix
                'usage_limit' => $this->usage_limit ?: null, // 👈 Fix
                'usage_limit_per_user' => $this->usage_limit_per_user ?: null, // 👈 Fix
                'description' => $this->description ?: null, // 👈 Fix
                'status' => $this->status ? 1 : 0,
            ]);

            // Sync relationships
            if (! empty($this->categories)) {
                $coupon->categories()->sync($this->categories);
            }

            if (! empty($this->brands)) {
                $coupon->brands()->sync($this->brands);
            }

            if (! empty($this->products)) {
                $coupon->products()->sync($this->products);
            }

            $message = $isDraft ? 'Coupon saved as draft!' : 'Coupon published successfully!';
            $this->dispatch('show-toast', type: 'success', message: $message);

            return redirect()->route('admin.coupon');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.coupons.coupons-create');
    }
}
