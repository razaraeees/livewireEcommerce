<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\ProductVariantImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImages;
use App\Models\ProductVariantImages;
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
    public $product_color;
    public $product_price;
    public $product_discount;
    public $product_weight;
    public $thumbnail_image;
    public $product_images = [];
    public $theme;
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

    // Variants
    public $variants = [];
    public $availableVariants = [];

    // Data for dropdowns
    public $categories = [];
    public $brands = [];

    public function mount()
    {
        $this->categories = Category::where('status', 1)->get();
        $this->brands = Brand::where('status', 1)->get();
        $this->availableVariants = Variant::where('status', 1)->with('values')->get();
        
        // Add one default variant
        $this->addVariant();
    }

    public function addVariant()
    {
        $this->variants[] = [
            'sku' => '',
            'barcode' => '',
            'price' => '',
            'sale_price' => '',
            'stock' => 0,
            'variant_slug' => '',
            'combination_label' => '',
            'weight' => '',
            'length' => '',
            'width' => '',
            'height' => '',
            'status' => true,
            'variant_values' => [],
            'images' => []
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function removeImage($key)
    {
        unset($this->product_images[$key]);
        $this->product_images = array_values($this->product_images);
    }

    public function removeVariantImage($variantIndex, $imageIndex)
    {
        if (isset($this->variants[$variantIndex]['images'][$imageIndex])) {
            unset($this->variants[$variantIndex]['images'][$imageIndex]);
            $this->variants[$variantIndex]['images'] = array_values($this->variants[$variantIndex]['images']);
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
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.combination_label' => 'required|string|max:255',
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
                'product_color' => $this->product_color,
                'product_price' => $this->product_price,
                'product_discount' => $this->product_discount,
                'product_weight' => $this->product_weight,
                'thumbnail_image' => $thumbnailPath,
                'theme' => $this->theme,
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
            if (empty($this->variants) && $this->product_images) {
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

            // Create variants
            foreach ($this->variants as $variantData) {
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'barcode' => $variantData['barcode'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'],
                    'stock' => $variantData['stock'],
                    'variant_slug' => $variantData['variant_slug'] ?: Str::slug($variantData['combination_label']),
                    'combination_label' => $variantData['combination_label'],
                    'weight' => $variantData['weight'],
                    'length' => $variantData['length'],
                    'width' => $variantData['width'],
                    'height' => $variantData['height'],
                    'status' => $variantData['status'] ? 1 : 0,
                ]);

                // Save variant values (Size, Color, etc.)
                if (!empty($variantData['variant_values'])) {
                    foreach ($variantData['variant_values'] as $variantId => $valueId) {
                        if ($valueId) {
                            ProductVariantValue::create([
                                'product_variant_id' => $productVariant->id,
                                'variant_id' => $variantId,
                                'variant_value_id' => $valueId,
                            ]);
                        }
                    }
                }

                // Upload variant images
                if (!empty($variantData['images'])) {
                    foreach ($variantData['images'] as $imgIndex => $image) {
                        $imagePath = $image->store('products/variants', 'public');
                        
                        ProductVariantImages::create([
                            'product_id' => $product->id,
                            'product_variant_id' => $productVariant->id,
                            'image_path' => $imagePath,
                            'order_by' => $imgIndex,
                            'status' => 1,
                        ]);
                    }
                }
            }

            $this->dispatch('show-toast', type: 'success', message: 'Product Created Successfully!');

            return redirect()->route('admin.product');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        try {
            // Upload thumbnail
            $thumbnailPath = null;
            if ($this->thumbnail_image) {
                $thumbnailPath = $this->thumbnail_image->store('products/thumbnails', 'public');
            }

            // Create product as draft
            $product = Product::create([
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'product_name' => $this->product_name ?: 'Draft Product',
                'product_slug' => $this->product_slug ?: Str::slug('draft-' . time()),
                'product_code' => $this->product_code,
                'product_color' => $this->product_color,
                'product_price' => $this->product_price ?: 0,
                'product_discount' => $this->product_discount,
                'product_weight' => $this->product_weight,
                'thumbnail_image' => $thumbnailPath,
                'theme' => $this->theme,
                'short_description' => $this->short_description,
                'long_description' => $this->long_description,
                'stock' => $this->stock,
                'stock_status' => $this->stock_status,
                'is_featured' => $this->is_featured,
                'order_by' => $this->order_by,
                'meta_title' => $this->meta_title,
                'meta_keywords' => $this->meta_keywords,
                'meta_description' => $this->meta_description,
                'status' => 0, // Draft status
            ]);

            $this->dispatch('show-toast', type: 'success', message: 'Draft Saved Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.products.products-create');
    }
}