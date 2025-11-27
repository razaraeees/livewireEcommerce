<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    // Public properties for filters
    public $search = '';

    public $filterStatus = '';

    public $filterCategory = '';

    public $filterBrand = '';

    protected $paginationTheme = 'bootstrap';

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterBrand()
    {
        $this->resetPage();
    }

    // Reset all filters
    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterCategory = '';
        $this->filterBrand = '';
        $this->resetPage();
    }

    // Toggle product status
    public function toggleStatus($slug)
    {
        $product = Product::where('product_slug', $slug)->firstOrFail();
        $product->update(['status' => ! $product->status]);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product status updated successfully!',
        ]);
    }

    // Toggle featured status
    public function toggleFeatured($slug)
    {
        $product = Product::where('product_slug', $slug)->firstOrFail();
        $product->update(['is_featured' => ! $product->is_featured]);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product featured status updated successfully!',
        ]);
    }

    // // Delete product with all images and variants
    // public function deleteProduct($slug)
    // {
    //     $product = Product::where('product_slug', $slug)->firstOrFail();

    //     // Delete thumbnail image
    //     if ($product->thumbnail_image && Storage::disk('public')->exists($product->thumbnail_image)) {
    //         Storage::disk('public')->delete($product->thumbnail_image);
    //     }

    //     // Delete gallery images
    //     if ($product->images) {
    //         $images = json_decode($product->images, true);
    //         if (is_array($images)) {
    //             foreach ($images as $image) {
    //                 if (Storage::disk('public')->exists($image)) {
    //                     Storage::disk('public')->delete($image);
    //                 }
    //             }
    //         }
    //     }

    //     // Delete variants and their images
    //     if ($product->variants) {
    //         foreach ($product->variants as $variant) {
    //             // Delete variant image
    //             if ($variant->image && Storage::disk('public')->exists($variant->image)) {
    //                 Storage::disk('public')->delete($variant->image);
    //             }

    //             // Delete variant
    //             $variant->delete();
    //         }
    //     }

    //     // Finally delete the product
    //     $product->delete();

    //     $this->dispatch('alert', [
    //         'type' => 'success',
    //         'message' => 'Product deleted successfully!',
    //     ]);
    // }

    public function delete($id)
    {
        $product = Product::with([
            'productVariants.images', // Load variants + their images
            'images',           // Load product gallery images
        ])->findOrFail($id);

        // === Step 1: Delete Product Variant Images (physical + DB) ===
        foreach ($product->productVariants as $variant) {
            // Delete physical files of variant images
            foreach ($variant->images as $image) {
                if ($image->image && Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                }
                $image->delete(); // Permanent delete from DB
            }

            // Delete variant values
            ProductVariantValue::where('product_variant_id', $variant->id)->delete();
        }

        // === Step 2: Delete Product Variants ===
        ProductVariant::where('product_id', $product->id)->delete();

        // === Step 3: Delete Product Gallery Images (physical + DB) ===
        foreach ($product->images as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        // === Step 4: Soft delete the main product ===
        $product->delete(); // This soft-deletes the product

        $this->dispatch('show-toast', type: 'success', message: 'Product deleted successfully!');

    }

    public function render()
    {
       $products = Product::query()
        ->when($this->search, function ($query) {
            $query->where(function($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                ->orWhere('product_code', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->filterStatus !== '', function ($query) {
            $query->where('status', $this->filterStatus);
        })
        ->when($this->filterCategory, function ($query) {
            $query->where('category_id', $this->filterCategory);
        })
        ->when($this->filterBrand, function ($query) {
            $query->where('brand_id', $this->filterBrand);
        })
        ->with(['category', 'brand'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);


        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('livewire.admin.products.products', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
