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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductsEdit extends Component
{
    use WithFileUploads;

    // Product ID
    public $productId;

    public $product;

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

    public $existing_thumbnail; // Store existing thumbnail path

    public $product_images = [];

    public $existing_images = []; // Store existing gallery images

    public $short_description;

    public $long_description;

    public $stock = 0;

    public $stock_status = 'in_stock';

    public $is_featured = false;

    public $order_by = 0;

    public $meta_title;

    public $meta_keywords;

    public $meta_description;

    public $status;

    // Variant Selection System
    public $selectedVariants = [];

    public $selectedVariantValues = [];

    public $generatedCombinations = [];

    public $existingVariants = []; // Store existing product variants

    // Data for dropdowns
    public $categories = [];

    public $brands = [];

    public $availableVariants;

    public $availableAttributes;

    public $productAttributes = [];

    public function mount($slug)
    {
        // Load product by slug
        $this->product = Product::where('product_slug', $slug)
            ->with([
                'images',
                'productVariants.variantValues.variant',
                'productVariants.variantValues.variantValue',
                'productVariants.images',
                'productAttributes.attribute',
                'productAttributes.attributeValue',
            ])
            ->firstOrFail();
        // dd($this->product->images);

        $this->productId = $this->product->id;

        $this->selectedVariants = [];
        $this->selectedVariantValues = [];
        // Load dropdowns
        $this->categories = Category::where('status', 1)->get();
        $this->brands = Brand::where('status', 1)->get();
        $this->availableVariants = Variant::where('status', 1)->with('variantValues')->get();
        $this->availableAttributes = \App\Models\Attribute::where('status', 1)
            ->with('attributeValue')
            ->get();

        // Populate basic product fields
        $this->category_id = $this->product->category_id;
        $this->brand_id = $this->product->brand_id;
        $this->product_name = $this->product->product_name;
        $this->product_slug = $this->product->product_slug;
        $this->product_code = $this->product->product_code;
        $this->product_price = $this->product->product_price;
        $this->product_discount = $this->product->product_discount;
        $this->product_weight = $this->product->product_weight;
        $this->existing_thumbnail = $this->product->thumbnail_image;
        $this->short_description = $this->product->short_description;
        $this->long_description = $this->product->long_description;
        $this->stock_status = $this->product->stock_status;
        $this->is_featured = (bool) $this->product->is_featured;
        $this->order_by = $this->product->order_by;
        $this->meta_title = $this->product->meta_title;
        $this->meta_keywords = $this->product->meta_keywords;
        $this->meta_description = $this->product->meta_description;
        $this->status = (bool) $this->product->status;

        // Load existing gallery images
        $this->existing_images = $this->product->images->toArray();

        // Load existing product attributes
        $this->loadExistingAttributes();

        // Load existing variants and populate the variant system
        $this->loadExistingVariants();
    }

    private function loadExistingAttributes()
    {
        if ($this->product->productAttributes->count() > 0) {
            $this->productAttributes = [];
            foreach ($this->product->productAttributes as $attr) {
                $this->productAttributes[] = [
                    'id' => $attr->id,
                    'attribute_id' => $attr->attribute_id,
                    'value_id' => $attr->attribute_value_id,
                ];
            }
        } else {
            $this->productAttributes = [['attribute_id' => '', 'value_id' => '']];
        }
    }

    private function loadExistingVariants()
    {
        if ($this->product->productVariants->count() > 0) {
            // Store existing variants for reference
            $this->existingVariants = $this->product->productVariants->toArray();

            // Extract unique variant types and their selected values
            $variantMap = [];

            foreach ($this->product->productVariants as $variant) {
                foreach ($variant->VariantValues as $pvv) {
                    $variantId = $pvv->variant_id;
                    $valueId = $pvv->variant_value_id;

                    if (! isset($variantMap[$variantId])) {
                        $variantMap[$variantId] = [];
                    }

                    if (! in_array($valueId, $variantMap[$variantId])) {
                        $variantMap[$variantId][] = $valueId;
                    }
                }
            }

            // Populate selectedVariants and selectedVariantValues
            $this->selectedVariants = array_keys($variantMap);
            $this->selectedVariantValues = $variantMap;

            // Generate combinations from existing data
            $this->generatedCombinations = [];

            foreach ($this->product->productVariants as $variant) {
                $combination = [];

                foreach ($variant->VariantValues as $pvv) {
                    $combination[$pvv->variant_id] = $pvv->variant_value_id;
                }

                // Build label
                $label = [];
                $slug = [];
                foreach ($combination as $vId => $valId) {
                    $v = $this->availableVariants->firstWhere('id', $vId);
                    $val = $v ? $v->variantValues->firstWhere('id', $valId) : null;
                    if ($v && $val) {
                        $label[] = "{$v->name}: {$val->value}";
                        $slug[] = Str::slug($val->value);
                    }
                }

                $keyParts = [];
                foreach ($combination as $vId => $valId) {
                    $keyParts[] = "{$vId}_{$valId}";
                }
                $combinationKey = implode('-', $keyParts);

                // Load existing images for this variant
                $existingVariantImages = [];
                foreach ($variant->images as $img) {
                    $existingVariantImages[] = [
                        'id' => $img->id,
                        'path' => $img->image,
                        'is_existing' => true,
                    ];
                }

                $this->generatedCombinations[] = [
                    'variant_id' => $variant->id, // Store DB ID for updates
                    'key' => $combinationKey,
                    'combination' => $combination,
                    'label' => implode(' | ', $label),
                    'slug' => implode('-', $slug),
                    'sku' => $variant->sku,
                    'barcode' => $variant->barcode,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'stock' => $variant->stock,
                    'weight' => $variant->weight,
                    'status' => (bool) $variant->status,
                    'images' => [],
                    'existing_images' => $existingVariantImages,
                ];
            }
        } else {
            // No existing variants
            $this->selectedVariants = [''];
            $this->selectedVariantValues = [];
            $this->generatedCombinations = [];
        }
    }

    public function addVariantSelection()
    {
        $this->selectedVariants[Str::random(8)] = '';
    }

    public function removeVariantSelection($key)
    {
        unset($this->selectedVariants[$key]);
        // selectedVariantValues mein key = variant_id hoti hai, isliye hum direct nahi delete karte
        // lekin generateCombinations() khud handle kar lega
        $this->generateCombinations();
    }

    public function updatedSelectedVariants($value, $name)
    {
        if (! empty($value)) {
            if (! isset($this->selectedVariantValues[$value])) {
                $this->selectedVariantValues[$value] = [];
            }
        }
        $this->generateCombinations();
    }

    public function updatedSelectedVariantValues($value, $key)
    {
        $this->generateCombinations();
    }

    public function generateCombinations()
    {
        $validSelections = [];

        foreach ($this->selectedVariantValues as $variantId => $valueIds) {
            if (! is_numeric($variantId) || $variantId <= 0) {
                continue;
            }

            $variant = $this->availableVariants->firstWhere('id', (int) $variantId);
            if (! $variant) {
                continue;
            }

            if (! is_array($valueIds)) {
                $valueIds = $valueIds ? [$valueIds] : [];
            }

            $cleanValueIds = array_filter(
                array_map('intval', $valueIds),
                fn ($v) => $v > 0
            );

            if (! empty($cleanValueIds)) {
                $validSelections[(int) $variantId] = array_values($cleanValueIds);
            }
        }

        if (empty($validSelections)) {
            $this->generatedCombinations = [];

            return;
        }

        $combinations = $this->cartesianProduct($validSelections);
        $newCombinations = [];

        foreach ($combinations as $idx => $combination) {
            $label = [];
            $slug = [];
            $isValid = true;

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

            $keyParts = [];
            foreach ($combination as $vId => $valId) {
                $keyParts[] = "{$vId}_{$valId}";
            }
            $combinationKey = implode('-', $keyParts);

            // Preserve existing data
            $existingData = collect($this->generatedCombinations)
                ->firstWhere('key', $combinationKey);

            $newCombinations[] = [
                'variant_id' => $existingData['variant_id'] ?? null,
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
                'status' => $existingData['status'] ?? true,
                'images' => $existingData['images'] ?? [],
                'existing_images' => $existingData['existing_images'] ?? [],
            ];
        }

        $this->generatedCombinations = $newCombinations;
    }

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

    public function deleteExistingImage($imageId)
    {
        $image = ProductImages::find($imageId);
        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            // Remove from existing_images array
            $this->existing_images = array_filter($this->existing_images, function ($img) use ($imageId) {
                return $img['id'] != $imageId;
            });
            $this->existing_images = array_values($this->existing_images);

            $this->dispatch('show-toast', type: 'success', message: 'Image deleted successfully!');
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

    public function deleteExistingVariantImage($combinationIndex, $imageId)
    {
        $image = ProductVariantImages::find($imageId);
        if ($image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();

            // Remove from existing_images in combination
            if (isset($this->generatedCombinations[$combinationIndex]['existing_images'])) {
                $this->generatedCombinations[$combinationIndex]['existing_images'] = array_filter(
                    $this->generatedCombinations[$combinationIndex]['existing_images'],
                    function ($img) use ($imageId) {
                        return $img['id'] != $imageId;
                    }
                );
                $this->generatedCombinations[$combinationIndex]['existing_images'] =
                    array_values($this->generatedCombinations[$combinationIndex]['existing_images']);
            }

            $this->dispatch('show-toast', type: 'success', message: 'Variant image deleted!');
        }
    }

    public function addAttributeRow()
    {
        $this->productAttributes[] = ['attribute_id' => '', 'value_id' => ''];
    }

    public function removeAttributeRow($index)
    {
        if ($index > 0) {
            unset($this->productAttributes[$index]);
            $this->productAttributes = array_values($this->productAttributes);
        }
    }

    public function updatedProductAttributes($value, $key)
    {
        if (strpos($key, '.attribute_id') !== false) {
            $index = explode('.', $key)[0];
            $this->productAttributes[$index]['value_id'] = '';
        }
    }

    public function update()
    {
        // Basic Product Validation
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'product_name' => 'required|string|max:255',
            'product_slug' => 'required|string|max:255|unique:products,product_slug,'.$this->productId,
            'product_code' => 'required|string|max:100|unique:products,product_code,'.$this->productId,
            'product_price' => 'required|numeric|min:0',
            'product_discount' => 'nullable|numeric|min:0|max:100',
            'product_weight' => 'nullable|numeric|min:0',
            'thumbnail_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,pre_order',
            'is_featured' => 'nullable|boolean',
            'order_by' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
        ]);

        // Variant Validation (if variants exist)
        if (! empty($this->generatedCombinations)) {
            $this->validate([
                'generatedCombinations.*.sku' => 'required|string|max:100',
                'generatedCombinations.*.price' => 'required|numeric|min:0',
                'generatedCombinations.*.stock' => 'required|integer|min:0',
                'generatedCombinations.*.barcode' => 'nullable|string|max:100',
                'generatedCombinations.*.sale_price' => 'nullable|numeric|min:0',
                'generatedCombinations.*.weight' => 'nullable|numeric|min:0',
                'generatedCombinations.*.images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
        }

        DB::beginTransaction();

        try {
            // Handle thumbnail update
            $thumbnailPath = $this->existing_thumbnail;
            if ($this->thumbnail_image) {
                // Delete old thumbnail
                if ($this->existing_thumbnail) {
                    Storage::disk('public')->delete($this->existing_thumbnail);
                }
                $thumbnailPath = $this->thumbnail_image->store('products/thumbnails', 'public');
            }

            // Update product
            $this->product->update([
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

            // Upload new gallery images (only if no variants)
            if (empty($this->generatedCombinations) && $this->product_images) {
                $maxOrder = ProductImages::where('product_id', $this->product->id)->max('sort_order') ?? 0;
                foreach ($this->product_images as $index => $image) {
                    $imagePath = $image->store('products/gallery', 'public');
                    ProductImages::create([
                        'product_id' => $this->product->id,
                        'image' => $imagePath,
                        'sort_order' => $maxOrder + $index + 1,
                    ]);
                }
            }

            // Update Product Attributes
            \App\Models\ProductAttribute::where('product_id', $this->product->id)->delete();

            foreach ($this->productAttributes as $attr) {
                if (! empty($attr['attribute_id']) && ! empty($attr['value_id'])) {
                    \App\Models\ProductAttribute::create([
                        'product_id' => $this->product->id,
                        'attribute_id' => $attr['attribute_id'],
                        'attribute_value_id' => $attr['value_id'],
                    ]);
                }
            }

            // Handle Variants Update
            $this->updateVariants();

            DB::commit();

            $this->dispatch('show-toast', type: 'success', message: 'Product Updated Successfully!');

            return redirect()->route('admin.product');

        } catch (\Exception $e) {
            DB::rollBack();

            // Rollback uploaded files if any error occurs
            if (isset($thumbnailPath) && $thumbnailPath !== $this->existing_thumbnail) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            Log::error('Product update error: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

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

    private function updateVariants()
    {
        // Get all existing variant IDs
        $existingVariantIds = collect($this->generatedCombinations)
            ->pluck('variant_id')
            ->filter()
            ->toArray();

        // Delete variants that are no longer in the form
        ProductVariant::where('product_id', $this->product->id)
            ->whereNotIn('id', $existingVariantIds)
            ->each(function ($variant) {
                // Delete variant images from storage
                foreach ($variant->images as $img) {
                    if (Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                    $img->delete();
                }
                // Delete variant values
                $variant->VariantValues()->delete();
                // Delete variant
                $variant->delete();
            });

        // Update or create variants
        foreach ($this->generatedCombinations as $combination) {
            if (! empty($combination['variant_id'])) {
                // Update existing variant
                $productVariant = ProductVariant::find($combination['variant_id']);
                if ($productVariant) {
                    $productVariant->update([
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
                }
            } else {
                // Create new variant
                $productVariant = ProductVariant::create([
                    'product_id' => $this->product->id,
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

                // Save variant values for new variant
                foreach ($combination['combination'] as $variantId => $valueId) {
                    ProductVariantValue::create([
                        'product_variant_id' => $productVariant->id,
                        'variant_id' => $variantId,
                        'variant_value_id' => $valueId,
                    ]);
                }
            }

            // Upload new variant images
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
    }

    public function render()
    {
        return view('livewire.admin.products.products-edit', [
            'availableVariants' => $this->availableVariants,
            'availableAttributes' => $this->availableAttributes,
        ]);
    }
}
