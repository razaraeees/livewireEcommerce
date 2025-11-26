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
use Illuminate\Support\Facades\Log;
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
    // public $product_color;
    public $product_price;
    public $product_discount;
    public $product_weight;
    public $thumbnail_image;
    public $product_images = [];
    // public $theme;
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
        
        // Load attributes with their values (assuming you have Attribute and AttributeValue models)
        $this->availableAttributes = \App\Models\Attribute::where('status', 1)
            ->with('attributeValue')
            ->get();

        // Initialize with one empty variant selection
        $this->selectedVariants = [''];
        $this->selectedVariantValues = [];
        $this->generatedCombinations = [];
        
        // Initialize with one empty attribute row
        $this->productAttributes = [['attribute_id' => '', 'value_id' => '']];
    }

    public function addVariantSelection()
    {
        $this->selectedVariants[] = '';
    }

    public function updatedSelectedVariants($value, $name)
    {
        if (!empty($value)) {
            if (!isset($this->selectedVariantValues[$value])) {
                $this->selectedVariantValues[$value] = [];
            }
        }
        // Auto-generate when variant changes
        $this->generateCombinations();
    }

    public function removeVariantSelection($index)
    {
        $variantId = $this->selectedVariants[$index] ?? null;

        unset($this->selectedVariants[$index]);
        $this->selectedVariants = array_values($this->selectedVariants);

        if ($variantId && isset($this->selectedVariantValues[$variantId])) {
            unset($this->selectedVariantValues[$variantId]);
        }

        $this->generateCombinations();
    }

    // This will auto-trigger when any checkbox is selected/deselected
    public function updatedSelectedVariantValues($value, $key)
    {
        Log::info('Variant Values Updated:', [
            'key' => $key,
            'value' => $value,
            'all_values' => $this->selectedVariantValues
        ]);
        
        // Auto-generate combinations whenever values change
        $this->generateCombinations();
    }

    // FIXED: Generate all possible combinations with proper debugging
    public function generateCombinations()
    {
        Log::info('=== Generate Combinations Started ===');
        Log::info('Selected Variant Values:', $this->selectedVariantValues);

        // Step 1: Clean and validate selections
        $validSelections = [];
        
        foreach ($this->selectedVariantValues as $variantId => $valueIds) {
            // Skip non-numeric or invalid IDs
            if (!is_numeric($variantId) || $variantId <= 0) {
                Log::warning("Skipping invalid variant ID: {$variantId}");
                continue;
            }

            // Find the variant
            $variant = $this->availableVariants->firstWhere('id', (int)$variantId);
            if (!$variant) {
                Log::warning("Variant not found: {$variantId}");
                continue;
            }

            // Clean value IDs
            if (!is_array($valueIds)) {
                $valueIds = $valueIds ? [$valueIds] : [];
            }
            
            // Filter only valid numeric IDs
            $cleanValueIds = array_filter(
                array_map('intval', $valueIds), 
                fn($v) => $v > 0
            );

            if (!empty($cleanValueIds)) {
                $validSelections[(int)$variantId] = array_values($cleanValueIds);
                Log::info("Valid selection for {$variant->name}:", $cleanValueIds);
            }
        }

        Log::info('Valid Selections:', $validSelections);

        // Step 2: Clear if no valid selections
        if (empty($validSelections)) {
            Log::info('No valid selections, clearing combinations');
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
        Log::info('Generated combinations count:', ['count' => count($combinations)]);

        $newCombinations = [];

        foreach ($combinations as $idx => $combination) {
            $label = [];
            $slug = [];
            $isValid = true;

            // Build label for this combination
            foreach ($combination as $variantId => $valueId) {
                $variant = $this->availableVariants->firstWhere('id', (int)$variantId);
                if (!$variant) {
                    $isValid = false;
                    Log::error("Variant not found in combo: {$variantId}");
                    break;
                }

                $value = $variant->variantValues->firstWhere('id', (int)$valueId);
                if (!$value) {
                    $isValid = false;
                    Log::error("Value not found: variant={$variantId}, value={$valueId}");
                    break;
                }

                $label[] = "{$variant->name}: {$value->value}";
                $slug[] = Str::slug($value->value);
            }

            if (!$isValid || empty($label)) {
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
        Log::info('Final combinations count:', ['count' => count($newCombinations)]);
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
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'product_name' => 'required|string|max:255',
            'product_slug' => 'required|string|max:255|unique:products,product_slug',
            'product_price' => 'required|numeric|min:0',
            'thumbnail_image' => 'nullable|image|max:2048',
            'product_images.*' => 'nullable|image|max:2048',
            'stock' => 'nullable|integer|min:0',
        ]);

        try {
            // Upload thumbnail
            $thumbnailPath = null;
            if ($this->thumbnail_image) {
                $thumbnailPath = $this->thumbnail_image->store('products/thumbnails', 'public');
            }

            // Create product
            $product = Product::create([
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'product_name' => $this->product_name,
                'product_slug' => $this->product_slug ?: Str::slug($this->product_name),
                'product_code' => $this->product_code,
                // 'product_color' => $this->product_color,
                'product_price' => $this->product_price,
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
                'status' => $this->status ? 1 : 0,
            ]);

            // Upload gallery images (only if no variants)
            if (empty($this->generatedCombinations) && $this->product_images) {
                foreach ($this->product_images as $index => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    ProductImages::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'order_by' => $index,
                        'status' => 1,
                    ]);
                }
            }

            // Save Product Attributes (Specifications)
            foreach ($this->productAttributes as $attr) {
                if (!empty($attr['attribute_id']) && !empty($attr['value_id'])) {
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
                    'barcode' => $combination['barcode'],
                    'price' => $combination['price'],
                    'sale_price' => $combination['sale_price'],
                    'stock' => $combination['stock'],
                    'variant_slug' => $combination['slug'],
                    'combination_label' => $combination['label'],
                    'weight' => $combination['weight'],
                    // 'length' => $combination['length'],
                    // 'width' => $combination['width'],
                    // 'height' => $combination['height'],
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
                if (!empty($combination['images'])) {
                    foreach ($combination['images'] as $imgIndex => $image) {
                        $imagePath = $image->store('products/variants', 'public');
                        ProductVariantImages::create([
                            // 'product_id' => $product->id,
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
                // 'product_color' => $this->product_color,
                'product_price' => $this->product_price ?: 0,
                'product_discount' => $this->product_discount,
                'product_weight' => $this->product_weight,
                'thumbnail_image' => $thumbnailPath,
                // 'theme' => $this->theme,
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