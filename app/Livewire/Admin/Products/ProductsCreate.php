<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductVariant;
use App\Models\ProductVariantImages;
use App\Models\ProductVariantValue;
use App\Models\Variant;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductsCreate extends Component
{
    use WithFileUploads;

    // Product fields
    public $category_id;

    public $brand_id;

    public $product_name;

    public $product_slug;

    public $product_code;

    public $product_price;

    public $product_discount;

    public $product_weight;

    public $thumbnail_image;

    public $product_images = [];

    public $short_description;

    public $long_description;

    public $stock = 0;

    public $stock_status = 'in_stock';

    public $is_featured = false;

    public $order_by = 0;

    public $meta_title;

    public $meta_keywords;

    public $meta_description;

    public $status = true;

    // Variant Selection System
    public $selectedVariants = [];

    public $selectedVariantValues = [];

    public $generatedCombinations = [];

    // Data for dropdowns
    public $categories = [];

    public $brands = [];

    public $availableVariants;

    public $availableAttributes;

    public $productAttributes = [];

    public function mount()
    {
        $this->categories = Category::where('status', 1)->get();
        $this->brands = Brand::where('status', 1)->get();
        $this->availableVariants = Variant::where('status', 1)->with('variantValues')->get();
        $this->availableAttributes = \App\Models\Attribute::where('status', 1)
            ->with('attributeValue')
            ->get();

        //  UNIQUE KEY ke saath initialize
        $this->selectedVariants = [Str::random(8) => ''];
        $this->selectedVariantValues = [];
        $this->generatedCombinations = [];
        $this->productAttributes = [['attribute_id' => '', 'value_id' => '']];
    }

    public function addVariantSelection()
    {
        $this->selectedVariants[Str::random(8)] = '';
    }

    public function updatedSelectedVariants($value, $name)
    {
        if (! empty($value)) {
            if (! isset($this->selectedVariantValues[$value])) {
                $this->selectedVariantValues[$value] = [];
            }
        }
        // Auto-generate when variant changes
        $this->generateCombinations();
    }

    public function removeVariantSelection($key)
    {
        $variantId = $this->selectedVariants[$key] ?? null;
        unset($this->selectedVariants[$key]);

        if ($variantId && isset($this->selectedVariantValues[$variantId])) {
            unset($this->selectedVariantValues[$variantId]);
        }

        $this->generateCombinations();
    }

    // This will auto-trigger when any checkbox is selected/deselected
    public function updatedSelectedVariantValues($value, $key)
    {
        // Auto-generate combinations whenever values change
        $this->generateCombinations();
    }

    // FIXED: Generate all possible combinations with proper debugging
    public function generateCombinations()
    {
        // Step 1: Clean and validate selections
        $validSelections = [];

        foreach ($this->selectedVariantValues as $variantId => $valueIds) {
            // Skip non-numeric or invalid IDs
            if (! is_numeric($variantId) || $variantId <= 0) {
                continue;
            }

            // Find the variant
            $variant = $this->availableVariants->firstWhere('id', (int) $variantId);
            if (! $variant) {
                continue;
            }

            // Clean value IDs
            if (! is_array($valueIds)) {
                $valueIds = $valueIds ? [$valueIds] : [];
            }

            // Filter only valid numeric IDs
            $cleanValueIds = array_filter(
                array_map('intval', $valueIds),
                fn ($v) => $v > 0
            );

            if (! empty($cleanValueIds)) {
                $validSelections[(int) $variantId] = array_values($cleanValueIds);
            }
        }

        // Step 2: Clear if no valid selections
        if (empty($validSelections)) {
            $this->generatedCombinations = [];

            return;
        }

        // Need at least 1 variant with values to generate
        if (count($validSelections) < 1) {
            $this->generatedCombinations = [];

            return;
        }

        // Step 3: Generate Cartesian Product
        $combinations = $this->cartesianProduct($validSelections);

        $newCombinations = [];

        foreach ($combinations as $idx => $combination) {
            $label = [];
            $slug = [];
            $isValid = true;

            // Build label for this combination
            foreach ($combination as $variantId => $valueId) {
                $variant = $this->availableVariants->firstWhere('id', (int) $variantId);
                if (! $variant) {
                    $isValid = false;
                    break;
                }

                $value = $variant->variantValues->firstWhere('id', (int) $valueId);
                if (! $value) {
                    $isValid = false;
                    break;
                }

                $label[] = "{$variant->name}: {$value->value}";
                $slug[] = Str::slug($value->value);
            }

            if (! $isValid || empty($label)) {
                continue;
            }

            // Create unique key
            $keyParts = [];
            foreach ($combination as $vId => $valId) {
                $keyParts[] = "{$vId}_{$valId}";
            }
            $combinationKey = implode('-', $keyParts);

            // Preserve existing data
            $existingData = collect($this->generatedCombinations)
                ->firstWhere('key', $combinationKey);

            $newCombinations[] = [
                'key' => $combinationKey,
                'combination' => $combination,
                'label' => implode(' | ', $label),
                'slug' => implode('-', $slug),
                'sku' => $existingData['sku'] ?? '',
                'barcode' => $existingData['barcode'] ?? '',
                'price' => $existingData['price'] ?? '',
                'sale_price' => $existingData['sale_price'] ?? '',
                'stock' => $existingData['stock'] ?? 0,
                'weight' => $existingData['weight'] ?? '',
                'length' => $existingData['length'] ?? '',
                'width' => $existingData['width'] ?? '',
                'height' => $existingData['height'] ?? '',
                'status' => $existingData['status'] ?? true,
                'images' => $existingData['images'] ?? [],
            ];
        }

        $this->generatedCombinations = $newCombinations;
    }

    // FIXED: Cartesian product helper
    private function cartesianProduct($arrays)
    {
        if (empty($arrays)) {
            return [[]];
        }

        $result = [[]];

        foreach ($arrays as $variantId => $valueIds) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($valueIds as $valueId) {
                    $newItem = $resultItem;
                    $newItem[$variantId] = $valueId;
                    $temp[] = $newItem;
                }
            }
            $result = $temp;
        }

        return $result;
    }

    public function removeImage($key)
    {
        unset($this->product_images[$key]);
        $this->product_images = array_values($this->product_images);
    }

    // Product Attributes Management
    public function addAttributeRow()
    {
        $this->productAttributes[] = ['attribute_id' => '', 'value_id' => ''];
    }

    public function removeAttributeRow($index)
    {
        if ($index > 0) { // Keep at least one row
            unset($this->productAttributes[$index]);
            $this->productAttributes = array_values($this->productAttributes);
        }
    }

    // When attribute changes, reset its value
    public function updatedProductAttributes($value, $key)
    {
        // If attribute_id changed, clear value_id for that row
        if (strpos($key, '.attribute_id') !== false) {
            $index = explode('.', $key)[0];
            $this->productAttributes[$index]['value_id'] = '';
        }
    }

    public function removeCombinationImage($combinationIndex, $imageIndex)
    {
        if (isset($this->generatedCombinations[$combinationIndex]['images'][$imageIndex])) {
            unset($this->generatedCombinations[$combinationIndex]['images'][$imageIndex]);
            $this->generatedCombinations[$combinationIndex]['images'] =
                array_values($this->generatedCombinations[$combinationIndex]['images']);
        }
    }

    public function store()
    {
        // Basic Product Validation
        $this->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'product_name' => 'required|max:255',
            'product_slug' => 'required|max:255|unique:products,product_slug',
            'product_code' => 'required|max:100|unique:products,product_code',
            'product_price' => 'required|min:0',
            'product_discount' => 'nullable|min:0|max:100',
            'product_weight' => 'nullable|min:0',

            'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'short_description' => 'nullable|max:500',
            'long_description' => 'nullable',

            'stock' => 'required|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,pre_order',
            'is_featured' => 'nullable',
            'order_by' => 'nullable|min:0',

            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',

            'status' => 'required|in:0,1',
        ]);

        // Variant Validation (agar variants select kiye hain)
        if (! empty($this->generatedCombinations)) {
            $this->validate([
                'generatedCombinations.*.sku' => 'required|max:100',
                'generatedCombinations.*.price' => 'required|min:0',
                'generatedCombinations.*.stock' => 'required|min:0',
                'generatedCombinations.*.barcode' => 'nullable|max:100',
                'generatedCombinations.*.sale_price' => 'nullable|min:0',
                'generatedCombinations.*.weight' => 'nullable|min:0',

                'generatedCombinations.*.images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

        }

        try {
            // Upload thumbnail
            $thumbnailPath = null;
            if ($this->thumbnail_image) {
                $thumbnailPath = $this->thumbnail_image->store('products/thumbnails', 'public');
            }

            // Create product
            $product = Product::create([
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id ?? null,
                'product_name' => $this->product_name,
                'product_slug' => $this->product_slug ?: Str::slug($this->product_name),
                'product_code' => $this->product_code ?: null,
                'product_color' => null,
                'product_price' => $this->product_price,
                'product_discount' => $this->product_discount ?: null,
                'product_weight' => $this->product_weight ?: null,
                'thumbnail_image' => $thumbnailPath,
                'short_description' => $this->short_description ?: null,
                'long_description' => $this->long_description ?: null,
                'stock' => $this->stock ?? 0,
                'stock_status' => $this->stock_status ?? 'in_stock',
                'is_featured' => $this->is_featured ? 1 : 0,
                'order_by' => $this->order_by ?: null,
                'meta_title' => $this->meta_title ?: null,
                'meta_keywords' => $this->meta_keywords ?: null,
                'meta_description' => $this->meta_description ?: null,
                'status' => $this->status ? 1 : 0,
            ]);

            // Upload gallery images (only if no variants)
            if (empty($this->generatedCombinations) && $this->product_images) {
                foreach ($this->product_images as $index => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    ProductImages::create([
                        'product_id' => $product->id,
                        'image' => $imagePath,
                        'sort_order' => $index,
                    ]);
                }
            }

            // Save Product Attributes (Specifications)
            foreach ($this->productAttributes as $attr) {
                if (! empty($attr['attribute_id']) && ! empty($attr['value_id'])) {
                    \App\Models\ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => $attr['attribute_id'],
                        'attribute_value_id' => $attr['value_id'],
                    ]);
                }
            }

            // Create variants from generated combinations
            foreach ($this->generatedCombinations as $combination) {
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $combination['sku'],
                    'barcode' => $combination['barcode'] ?: null,
                    'price' => $combination['price'],
                    'sale_price' => $combination['sale_price'] ?: null,
                    'stock' => $combination['stock'] ?? 0,
                    'variant_slug' => $combination['slug'],
                    'combination_label' => $combination['label'],
                    'weight' => $combination['weight'] ?: null,
                    'status' => $combination['status'] ? 1 : 0,
                ]);

                // Save variant values
                foreach ($combination['combination'] as $variantId => $valueId) {
                    ProductVariantValue::create([
                        'product_variant_id' => $productVariant->id,
                        'variant_id' => $variantId,
                        'variant_value_id' => $valueId,
                    ]);
                }

                // Upload variant images
                if (! empty($combination['images'])) {
                    foreach ($combination['images'] as $imgIndex => $image) {
                        $imagePath = $image->store('products/variants', 'public');
                        ProductVariantImages::create([
                            'product_variant_id' => $productVariant->id,
                            'image' => $imagePath,
                            'sort_order' => $imgIndex,
                        ]);
                    }
                }
            }

            $this->dispatch('show-toast', type: 'success', message: 'Product Created Successfully!');

            return redirect()->route('admin.product');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: '.$e->getMessage());

        }
    }

    public function getCategoryTreeProperty()
    {
        $categories = Category::all();

        return $this->buildTree($categories);
    }

    private function buildTree($categories, $parentId = null)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {

                $children = $this->buildTree($categories, $category->id);
                $category->children = $children;

                $branch[] = $category;
            }
        }

        return $branch;
    }

    public function saveDraft()
    {
        try {
            $thumbnailPath = null;
            if ($this->thumbnail_image) {
                $thumbnailPath = $this->thumbnail_image->store('products/thumbnails', 'public');
            }

            Product::create([
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'product_name' => $this->product_name ?: 'Draft Product',
                'product_slug' => $this->product_slug ?: Str::slug('draft-'.time()),
                'product_code' => $this->product_code,
                'product_price' => $this->product_price ?: 0,
                'product_discount' => $this->product_discount,
                'product_weight' => $this->product_weight,
                'thumbnail_image' => $thumbnailPath,
                'short_description' => $this->short_description,
                'long_description' => $this->long_description,
                'stock' => $this->stock,
                'stock_status' => $this->stock_status,
                'is_featured' => $this->is_featured,
                'order_by' => $this->order_by,
                'meta_title' => $this->meta_title,
                'meta_keywords' => $this->meta_keywords,
                'meta_description' => $this->meta_description,
                'status' => 0,
            ]);

            $this->dispatch('show-toast', type: 'success', message: 'Draft Saved Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: '.$e->getMessage());

        }
    }

    public function render()
    {
        return view('livewire.admin.products.products-create', [
            'availableVariants' => $this->availableVariants,
            'availableAttributes' => $this->availableAttributes,
        ]);
    }
}
